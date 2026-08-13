/**
 * SagaGraph - Librería de visualización de topología de dispositivos
 * Inspirada en JointJS+ Organizational Chart + Dynamic Tooltip Content
 * Para SAGADEGRO - Sistema de Gestión de Dispositivos
 * 
 * @version 1.0.0
 * @author Desarrollador SAGADEGRO
 */

(function(global) {
  'use strict';

  // ============================================================
  // CONFIGURACIÓN POR DEFECTO
  // ============================================================
  const DEFAULTS = {
    nodeWidth: 240,
    nodeHeight: 185,
    gapX: 80,
    gapY: 120,
    minZoom: 0.15,
    maxZoom: 3,
    zoomStep: 0.1,
    animationDuration: 300,
    colors: {
      edge: '#cbd5e1',
      edgeHighlight: '#0f172a',
      edgeDash: [6, 4],
      background: '#f8fafc'
    },
    icons: {
      router: '🌐',
      modem: '📡',
      camera: '📹',
      ap: '📶',
      switch: '🔀',
      firewall: '🛡️',
      server: '🖥️',
      other: '🔌'
    },
    labels: {
      router: 'Router',
      modem: 'Módem',
      camera: 'Cámara',
      ap: 'Access Point',
      switch: 'Switch',
      firewall: 'Firewall',
      server: 'Servidor',
      other: 'Otro Dispositivo'
    }
  };

  // ============================================================
  // CLASE PRINCIPAL: SagaGraph
  // ============================================================
  class SagaGraph {
    constructor(containerId, options = {}) {
      this.container = document.getElementById(containerId);
      if (!this.container) {
        throw new Error(`SagaGraph: No se encontró el contenedor #${containerId}`);
      }

      this.options = { ...DEFAULTS, ...options };
      this.devices = [];
      this.nodePositions = new Map();
      this.edges = [];

      // Estado de la cámara
      this.camera = { x: 0, y: 0, zoom: 1 };
      this.isPanning = false;
      this.panStart = { x: 0, y: 0 };
      this.cameraStart = { x: 0, y: 0 };

      // Drag de nodos
      this.draggedNode = null;
      this.dragOffset = { x: 0, y: 0 };
      this.dragStartPos = { x: 0, y: 0 };

      // Highlight
      this.highlightedNode = null;
      this.selectedNode = null;

      // Callbacks
      this.callbacks = {
        onNodeClick: null,
        onNodeDoubleClick: null,
        onRegistrarAcceso: null,
        onVerDetalle: null,
        onTooltipRender: null,
        onLayoutComplete: null
      };

      // Pool de elementos DOM para virtualización
      this.nodeElements = new Map();
      this.tooltipElement = null;
      this.animationFrame = null;
      this.lastRenderTime = 0;

      this._init();
    }

    // ============================================================
    // INICIALIZACIÓN
    // ============================================================
    _init() {
      this._createDOM();
      this._setupEvents();
      this._createTooltip();
      this._resize();

      window.addEventListener('resize', () => {
        this._resize();
        this._renderEdges();
      });
    }

    _createDOM() {
      const container = this.container;
      container.style.position = 'relative';
      container.style.overflow = 'hidden';
      container.style.background = this.options.colors.background;
      container.style.userSelect = 'none';
      container.style.webkitUserSelect = 'none';

      // Canvas para edges
      this.canvas = document.createElement('canvas');
      this.canvas.className = 'sg-canvas';
      this.canvas.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;z-index:1;';
      container.appendChild(this.canvas);
      this.ctx = this.canvas.getContext('2d');

      // Capa HTML para nodos
      this.htmlLayer = document.createElement('div');
      this.htmlLayer.className = 'sg-html-layer';
      this.htmlLayer.style.cssText = 'position:absolute;top:0;left:0;width:100%;height:100%;z-index:2;pointer-events:none;transform-origin:0 0;';
      container.appendChild(this.htmlLayer);

      // Mini-map (opcional)
      this.minimap = document.createElement('div');
      this.minimap.className = 'sg-minimap';
      this.minimap.style.cssText = 'position:absolute;bottom:12px;left:12px;width:160px;height:100px;background:rgba(255,255,255,0.9);border:1px solid #e2e8f0;border-radius:8px;z-index:40;box-shadow:0 2px 8px rgba(0,0,0,0.08);display:none;';
      container.appendChild(this.minimap);

      // Toolbar
      this.toolbar = document.createElement('div');
      this.toolbar.className = 'sg-toolbar';
      this.toolbar.innerHTML = `
        <button class="sg-btn sg-btn-zoomin" title="Zoom In">+</button>
        <button class="sg-btn sg-btn-zoomout" title="Zoom Out">−</button>
        <button class="sg-btn sg-btn-fit" title="Ajustar a pantalla">⛶</button>
        <button class="sg-btn sg-btn-layout" title="Reorganizar layout">↻</button>
        <button class="sg-btn sg-btn-reset" title="Reset vista">⌂</button>
      `;
      this.toolbar.style.cssText = 'position:absolute;bottom:16px;right:16px;z-index:50;display:flex;gap:4px;background:#fff;padding:6px;border-radius:10px;box-shadow:0 2px 12px rgba(0,0,0,0.1);';
      container.appendChild(this.toolbar);

      // Info panel
      this.infoPanel = document.createElement('div');
      this.infoPanel.className = 'sg-info';
      this.infoPanel.style.cssText = 'position:absolute;top:12px;left:12px;z-index:50;background:#fff;padding:10px 14px;border-radius:8px;font-size:12px;color:#475569;box-shadow:0 2px 8px rgba(0,0,0,0.06);font-family:system-ui,sans-serif;';
      container.appendChild(this.infoPanel);

      this._bindToolbar();
    }

    _bindToolbar() {
      this.toolbar.querySelector('.sg-btn-zoomin').addEventListener('click', () => this.zoom(1.2));
      this.toolbar.querySelector('.sg-btn-zoomout').addEventListener('click', () => this.zoom(0.8));
      this.toolbar.querySelector('.sg-btn-fit').addEventListener('click', () => this.fitToContent());
      this.toolbar.querySelector('.sg-btn-layout').addEventListener('click', () => this.layout());
      this.toolbar.querySelector('.sg-btn-reset').addEventListener('click', () => this.resetView());
    }

    _createTooltip() {
      this.tooltipElement = document.createElement('div');
      this.tooltipElement.className = 'sg-tooltip';
      this.tooltipElement.style.cssText = `
        position: absolute;
        z-index: 200;
        background: #0f172a;
        color: white;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 12px;
        min-width: 240px;
        max-width: 320px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.25);
        pointer-events: none;
        opacity: 0;
        transform: translateY(6px) scale(0.96);
        transition: opacity 0.2s, transform 0.2s;
        font-family: system-ui, sans-serif;
      `;
      this.container.appendChild(this.tooltipElement);
    }

    _resize() {
      const dpr = window.devicePixelRatio || 1;
      const w = this.container.clientWidth;
      const h = this.container.clientHeight;
      this.canvas.width = w * dpr;
      this.canvas.height = h * dpr;
      this.canvas.style.width = w + 'px';
      this.canvas.style.height = h + 'px';
      this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      this.width = w;
      this.height = h;
    }

    // ============================================================
    // EVENTOS
    // ============================================================
    _setupEvents() {
      // Pan del canvas
      this.container.addEventListener('mousedown', (e) => {
        if (e.target === this.canvas || e.target === this.htmlLayer || e.target === this.container) {
          this.isPanning = true;
          this.panStart = { x: e.clientX, y: e.clientY };
          this.cameraStart = { x: this.camera.x, y: this.camera.y };
          this.container.style.cursor = 'grabbing';
        }
      });

      document.addEventListener('mousemove', (e) => this._onMouseMove(e));
      document.addEventListener('mouseup', () => this._onMouseUp());

      // Zoom con rueda
      this.container.addEventListener('wheel', (e) => {
        e.preventDefault();
        const factor = e.deltaY < 0 ? 1 + this.options.zoomStep : 1 - this.options.zoomStep;
        this.zoomAt(factor, e.clientX, e.clientY);
      }, { passive: false });

      // Touch events para móvil
      this.container.addEventListener('touchstart', (e) => this._onTouchStart(e), { passive: false });
      this.container.addEventListener('touchmove', (e) => this._onTouchMove(e), { passive: false });
      this.container.addEventListener('touchend', () => this._onTouchEnd());
    }

    _onMouseMove(e) {
      if (this.isPanning) {
        this.camera.x = this.cameraStart.x + (e.clientX - this.panStart.x);
        this.camera.y = this.cameraStart.y + (e.clientY - this.panStart.y);
        this._updateTransform();
        this._renderEdges();
      }

      if (this.draggedNode !== null) {
        const pos = this.nodePositions.get(this.draggedNode);
        const dx = (e.clientX - this.dragStartPos.x) / this.camera.zoom;
        const dy = (e.clientY - this.dragStartPos.y) / this.camera.zoom;
        pos.x = this.dragOffset.x + dx;
        pos.y = this.dragOffset.y + dy;
        pos.fixed = true;
        this._renderEdges();
        this._updateNodePosition(this.draggedNode);
      }
    }

    _onMouseUp() {
      this.isPanning = false;
      if (this.draggedNode !== null) {
        const el = this.nodeElements.get(this.draggedNode);
        if (el) el.classList.remove('sg-dragging');
        this.draggedNode = null;
      }
      this.container.style.cursor = 'default';
    }

    _onTouchStart(e) {
      if (e.touches.length === 1) {
        const touch = e.touches[0];
        this.isPanning = true;
        this.panStart = { x: touch.clientX, y: touch.clientY };
        this.cameraStart = { x: this.camera.x, y: this.camera.y };
      } else if (e.touches.length === 2) {
        this.isPanning = false;
        this.lastPinchDist = this._getPinchDist(e.touches);
        this.lastPinchZoom = this.camera.zoom;
      }
    }

    _onTouchMove(e) {
      e.preventDefault();
      if (e.touches.length === 1 && this.isPanning) {
        const touch = e.touches[0];
        this.camera.x = this.cameraStart.x + (touch.clientX - this.panStart.x);
        this.camera.y = this.cameraStart.y + (touch.clientY - this.panStart.y);
        this._updateTransform();
        this._renderEdges();
      } else if (e.touches.length === 2) {
        const dist = this._getPinchDist(e.touches);
        const factor = dist / this.lastPinchDist;
        const mid = this._getPinchMid(e.touches);
        this.zoomAt(factor * this.lastPinchZoom / this.camera.zoom, mid.x, mid.y);
      }
    }

    _onTouchEnd() {
      this.isPanning = false;
      this.lastPinchDist = null;
    }

    _getPinchDist(touches) {
      const dx = touches[0].clientX - touches[1].clientX;
      const dy = touches[0].clientY - touches[1].clientY;
      return Math.sqrt(dx * dx + dy * dy);
    }

    _getPinchMid(touches) {
      return {
        x: (touches[0].clientX + touches[1].clientX) / 2,
        y: (touches[0].clientY + touches[1].clientY) / 2
      };
    }

    // ============================================================
    // DATOS
    // ============================================================
    setData(devices) {
      this.devices = devices.map(d => ({ ...d }));
      this._buildEdges();
      this._updateInfo();
      return this;
    }

    _buildEdges() {
      this.edges = [];
      const deviceMap = new Map(this.devices.map(d => [d.id, d]));
      this.devices.forEach(device => {
        if (device.parentId && deviceMap.has(device.parentId)) {
          this.edges.push({
            from: device.parentId,
            to: device.id,
            source: deviceMap.get(device.parentId),
            target: device
          });
        }
      });
    }

    _updateInfo() {
      const total = this.devices.length;
      const active = this.devices.filter(d => d.status === 'active').length;
      const inactive = total - active;
      this.infoPanel.innerHTML = `
        <strong style="color:#0f172a;font-size:13px;">SAGADEGRO</strong> — Topología de Red<br>
        <span style="font-size:11px;color:#94a3b8">
          ${total} dispositivos · 
          <span style="color:#16a34a">${active} activos</span> · 
          <span style="color:#dc2626">${inactive} inactivos</span>
        </span>
      `;
    }

    // ============================================================
    // LAYOUT (ÁRBOL)
    // ============================================================
    layout() {
      const tree = this._buildTree();
      const startX = 0;
      const startY = 40;

      // Reset posiciones no fijas
      this.nodePositions.forEach((pos, id) => {
        if (!pos.fixed) {
          pos.x = 0;
          pos.y = 0;
        }
      });

      let totalW = 0;
      const widths = [];
      tree.roots.forEach(root => {
        const w = this._layoutNode(root, 0, 0).width;
        widths.push(w);
        totalW += w + this.options.gapX;
      });
      totalW -= this.options.gapX;

      let rx = startX - totalW / 2;
      tree.roots.forEach((root, i) => {
        this._layoutNode(root, rx + widths[i] / 2, startY);
        rx += widths[i] + this.options.gapX;
      });

      this._createNodeElements();
      this._renderEdges();
      this._updateTransform();

      if (this.callbacks.onLayoutComplete) {
        this.callbacks.onLayoutComplete(this.nodePositions);
      }

      return this;
    }

    _buildTree() {
      const map = new Map();
      this.devices.forEach(d => {
        map.set(d.id, { ...d, children: [] });
      });
      const roots = [];
      this.devices.forEach(d => {
        if (d.parentId && map.has(d.parentId)) {
          map.get(d.parentId).children.push(map.get(d.id));
        } else {
          roots.push(map.get(d.id));
        }
      });
      return { map, roots };
    }

    _layoutNode(node, x, y) {
      let pos = this.nodePositions.get(node.id);
      if (!pos) {
        pos = { x, y, fixed: false };
        this.nodePositions.set(node.id, pos);
      } else if (!pos.fixed) {
        pos.x = x;
        pos.y = y;
      }

      if (!node.children || node.children.length === 0) {
        return { width: this.options.nodeWidth };
      }

      let totalWidth = 0;
      const childWidths = [];
      node.children.forEach(child => {
        const cw = this._layoutNode(child, 0, 0).width;
        childWidths.push(cw);
        totalWidth += cw + this.options.gapX;
      });
      totalWidth -= this.options.gapX;

      let cx = x - totalWidth / 2;
      node.children.forEach((child, i) => {
        this._layoutNode(child, cx + childWidths[i] / 2, y + this.options.nodeHeight + this.options.gapY);
        cx += childWidths[i] + this.options.gapX;
      });

      return { width: Math.max(this.options.nodeWidth, totalWidth) };
    }

    // ============================================================
    // RENDERIZADO DE NODOS (CARDS)
    // ============================================================
    _createNodeElements() {
      // Limpiar layer
      this.htmlLayer.innerHTML = '';
      this.nodeElements.clear();

      this.devices.forEach(device => {
        const pos = this.nodePositions.get(device.id);
        if (!pos) return;

        const card = this._createCard(device);
        card.style.left = pos.x + 'px';
        card.style.top = pos.y + 'px';
        this.htmlLayer.appendChild(card);
        this.nodeElements.set(device.id, card);
      });
    }

    _createCard(device) {
      const card = document.createElement('div');
      card.className = 'sg-node';
      card.dataset.id = device.id;
      card.style.cssText = `
        position: absolute;
        width: ${this.options.nodeWidth}px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08), 0 0 1px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        transition: box-shadow 0.2s, transform 0.15s, opacity 0.2s;
        cursor: grab;
        overflow: hidden;
        font-family: system-ui, sans-serif;
        pointer-events: auto;
      `;

      const icon = this.options.icons[device.type] || this.options.icons.other;
      const label = this.options.labels[device.type] || device.type;
      const isActive = device.status === 'active';
      const statusColor = isActive ? '#16a34a' : '#dc2626';
      const statusBg = isActive ? '#dcfce7' : '#fee2e2';
      const statusText = isActive ? 'Activo' : 'Inactivo';

      card.innerHTML = `
        <div style="display:flex;align-items:center;gap:10px;padding:12px 14px;border-bottom:1px solid #f1f5f9;">
          <div style="width:38px;height:38px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:18px;background:${this._getTypeColor(device.type)};flex-shrink:0;">
            ${icon}
          </div>
          <div style="min-width:0;flex:1;">
            <div style="font-size:13px;font-weight:600;color:#1e293b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${this._escapeHtml(device.name)}</div>
            <div style="font-size:11px;color:#64748b;">${label}</div>
          </div>
        </div>
        <div style="padding:10px 14px;font-size:11px;color:#475569;line-height:1.6;">
          <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
            <span style="color:#94a3b8;">Estado</span>
            <span style="display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:600;padding:2px 8px;border-radius:999px;background:${statusBg};color:${statusColor};">
              <span style="width:5px;height:5px;border-radius:50%;background:${statusColor};"></span>
              ${statusText}
            </span>
          </div>
          <div style="display:flex;justify-content:space-between;margin-bottom:3px;">
            <span style="color:#94a3b8;">IP</span>
            <span style="font-weight:500;color:#334155;font-family:monospace;font-size:10px;">${device.ip || 'N/A'}</span>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:#94a3b8;">Ubicación</span>
            <span style="font-weight:500;color:#334155;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-align:right;">${this._escapeHtml(device.location || 'N/A')}</span>
          </div>
        </div>
        <div style="padding:10px 14px;border-top:1px solid #f1f5f9;display:flex;gap:8px;">
          <button class="sg-btn-acceso" style="flex:1;padding:7px 0;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;background:#0f172a;color:white;transition:background 0.15s;"
            onmouseover="this.style.background='#1e293b'" onmouseout="this.style.background='#0f172a'">
            <i class="fas fa-key"></i> Accesos
          </button>
          <button class="sg-btn-detalle" style="padding:7px 12px;border:none;border-radius:6px;font-size:11px;font-weight:600;cursor:pointer;background:#f8fafc;color:#475569;border:1px solid #e2e8f0;transition:background 0.15s;"
            onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
            <i class="fas fa-edit"></i>
          </button>
        </div>
      `;

      // Eventos
      card.addEventListener('mouseenter', (e) => this._showTooltip(e, device));
      card.addEventListener('mouseleave', () => this._hideTooltip());
      card.addEventListener('mousedown', (e) => this._startDragNode(e, device.id));
      card.addEventListener('click', (e) => {
        if (!this.draggedNode) {
          this._onNodeClick(device.id, e);
        }
      });
      card.addEventListener('dblclick', (e) => {
        if (this.callbacks.onNodeDoubleClick) {
          this.callbacks.onNodeDoubleClick(device, e);
        }
      });

      // Botones
      card.querySelector('.sg-btn-acceso').addEventListener('click', (e) => {
        e.stopPropagation();
        if (this.callbacks.onRegistrarAcceso) {
          this.callbacks.onRegistrarAcceso(device);
        } else {
          console.log('Registrar acceso:', device);
        }
      });

      card.querySelector('.sg-btn-detalle').addEventListener('click', (e) => {
        e.stopPropagation();
        if (this.callbacks.onVerDetalle) {
          this.callbacks.onVerDetalle(device);
        } else {
          console.log('Ver detalle:', device);
        }
      });

      return card;
    }

    _getTypeColor(type) {
      const colors = {
        router: '#dbeafe',
        modem: '#dcfce7',
        camera: '#fef3c7',
        ap: '#f3e8ff',
        switch: '#e0e7ff',
        firewall: '#fee2e2',
        server: '#cffafe',
        other: '#f1f5f9'
      };
      return colors[type] || colors.other;
    }

    _escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    _updateNodePosition(nodeId) {
      const el = this.nodeElements.get(nodeId);
      const pos = this.nodePositions.get(nodeId);
      if (el && pos) {
        el.style.left = pos.x + 'px';
        el.style.top = pos.y + 'px';
      }
    }

    // ============================================================
    // RENDERIZADO DE EDGES (CANVAS)
    // ============================================================
    _renderEdges() {
      const ctx = this.ctx;
      const w = this.width;
      const h = this.height;
      ctx.clearRect(0, 0, w, h);
      ctx.save();
      ctx.translate(this.camera.x, this.camera.y);
      ctx.scale(this.camera.zoom, this.camera.zoom);

      this.edges.forEach(edge => {
        const fromPos = this.nodePositions.get(edge.from);
        const toPos = this.nodePositions.get(edge.to);
        if (!fromPos || !toPos) return;

        const isHighlighted = this._isEdgeHighlighted(edge);
        const isDimmed = this.highlightedNode && !isHighlighted;

        ctx.beginPath();
        const startX = fromPos.x + this.options.nodeWidth / 2;
        const startY = fromPos.y + this.options.nodeHeight;
        const endX = toPos.x + this.options.nodeWidth / 2;
        const endY = toPos.y;
        const midY = (startY + endY) / 2;

        ctx.moveTo(startX, startY);
        ctx.bezierCurveTo(startX, midY, endX, midY, endX, endY);

        ctx.strokeStyle = isHighlighted ? this.options.colors.edgeHighlight : this.options.colors.edge;
        ctx.lineWidth = isHighlighted ? 2.5 : 1.5;
        ctx.globalAlpha = isDimmed ? 0.2 : (isHighlighted ? 1 : 0.7);

        if (!isHighlighted) {
          ctx.setLineDash(this.options.colors.edgeDash);
        }

        ctx.stroke();
        ctx.setLineDash([]);
        ctx.globalAlpha = 1;

        // Flecha
        ctx.beginPath();
        ctx.moveTo(endX, endY);
        ctx.lineTo(endX - 5, endY - 8);
        ctx.lineTo(endX + 5, endY - 8);
        ctx.closePath();
        ctx.fillStyle = isHighlighted ? this.options.colors.edgeHighlight : '#94a3b8';
        ctx.globalAlpha = isDimmed ? 0.2 : 1;
        ctx.fill();
        ctx.globalAlpha = 1;
      });

      ctx.restore();
    }

    _isEdgeHighlighted(edge) {
      if (!this.highlightedNode) return false;
      return edge.from === this.highlightedNode || edge.to === this.highlightedNode;
    }

    // ============================================================
    // TOOLTIP DINÁMICO
    // ============================================================
    _showTooltip(e, device) {
      const content = this.callbacks.onTooltipRender 
        ? this.callbacks.onTooltipRender(device)
        : this._defaultTooltipContent(device);

      this.tooltipElement.innerHTML = content;
      this.tooltipElement.classList.add('sg-tooltip-visible');

      const card = e.currentTarget;
      const rect = card.getBoundingClientRect();
      const contRect = this.container.getBoundingClientRect();

      let left = rect.left - contRect.left + rect.width / 2 - this.tooltipElement.offsetWidth / 2;
      let top = rect.top - contRect.top - this.tooltipElement.offsetHeight - 12;

      // Ajustar bordes
      if (left < 8) left = 8;
      if (left + this.tooltipElement.offsetWidth > contRect.width - 8) {
        left = contRect.width - this.tooltipElement.offsetWidth - 8;
      }
      if (top < 8) top = rect.bottom - contRect.top + 12;

      this.tooltipElement.style.left = left + 'px';
      this.tooltipElement.style.top = top + 'px';
    }

    _hideTooltip() {
      this.tooltipElement.classList.remove('sg-tooltip-visible');
    }

    _defaultTooltipContent(device) {
      const icon = this.options.icons[device.type] || this.options.icons.other;
      const label = this.options.labels[device.type] || device.type;
      const isActive = device.status === 'active';
      const statusColor = isActive ? '#4ade80' : '#f87171';
      const statusText = isActive ? 'Activo' : 'Inactivo';

      return `
        <div style="font-weight:700;font-size:13px;margin-bottom:10px;display:flex;align-items:center;gap:8px;">
          <span style="font-size:16px;">${icon}</span>
          ${this._escapeHtml(device.name)}
        </div>
        <div style="display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.08);">
          <span style="color:#94a3b8;">Tipo</span><span>${label}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.08);">
          <span style="color:#94a3b8;">Estado</span><span style="color:${statusColor}">${statusText}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.08);">
          <span style="color:#94a3b8;">IP</span><span style="font-family:monospace;">${device.ip || 'N/A'}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.08);">
          <span style="color:#94a3b8;">MAC</span><span style="font-family:monospace;">${device.mac || 'N/A'}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.08);">
          <span style="color:#94a3b8;">Ubicación</span><span>${this._escapeHtml(device.location || 'N/A')}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:3px 0;">
          <span style="color:#94a3b8;">ID Dispositivo</span><span>#${device.id}</span>
        </div>
      `;
    }

    // ============================================================
    // INTERACCIÓN CON NODOS
    // ============================================================
    _startDragNode(e, nodeId) {
      e.stopPropagation();
      this.draggedNode = nodeId;
      const pos = this.nodePositions.get(nodeId);
      this.dragOffset = { x: pos.x, y: pos.y };
      this.dragStartPos = { x: e.clientX, y: e.clientY };
      const el = this.nodeElements.get(nodeId);
      if (el) el.classList.add('sg-dragging');
    }

    _onNodeClick(nodeId, e) {
      this.selectedNode = nodeId;
      this.highlightNode(nodeId);
      if (this.callbacks.onNodeClick) {
        const device = this.devices.find(d => d.id === nodeId);
        this.callbacks.onNodeClick(device, e);
      }
    }

    highlightNode(nodeId) {
      if (this.highlightedNode === nodeId) {
        this.highlightedNode = null;
      } else {
        this.highlightedNode = nodeId;
      }

      // Actualizar opacidad de nodos
      this.nodeElements.forEach((el, id) => {
        if (!this.highlightedNode) {
          el.style.opacity = '1';
        } else if (this._isNodeRelated(id, this.highlightedNode)) {
          el.style.opacity = '1';
        } else {
          el.style.opacity = '0.35';
        }
      });

      this._renderEdges();
    }

    _isNodeRelated(nodeId, highlightId) {
      if (nodeId === highlightId) return true;
      // Verificar si es ancestro
      const device = this.devices.find(d => d.id === nodeId);
      if (device && device.parentId === highlightId) return true;
      // Verificar si es descendiente directo
      const highlighted = this.devices.find(d => d.id === highlightId);
      if (highlighted && highlighted.parentId === nodeId) return true;
      // Verificar cadena de ancestros
      let curr = device;
      while (curr && curr.parentId) {
        if (curr.parentId === highlightId) return true;
        curr = this.devices.find(d => d.id === curr.parentId);
      }
      return false;
    }

    // ============================================================
    // CÁMARA Y ZOOM
    // ============================================================
    zoom(factor) {
      const rect = this.container.getBoundingClientRect();
      this.zoomAt(factor, rect.left + rect.width / 2, rect.top + rect.height / 2);
    }

    zoomAt(factor, clientX, clientY) {
      const rect = this.container.getBoundingClientRect();
      const mx = clientX - rect.left;
      const my = clientY - rect.top;

      const worldX = (mx - this.camera.x) / this.camera.zoom;
      const worldY = (my - this.camera.y) / this.camera.zoom;

      let newZoom = this.camera.zoom * factor;
      newZoom = Math.max(this.options.minZoom, Math.min(this.options.maxZoom, newZoom));

      this.camera.x = mx - worldX * newZoom;
      this.camera.y = my - worldY * newZoom;
      this.camera.zoom = newZoom;

      this._updateTransform();
      this._renderEdges();
    }

    _updateTransform() {
      this.htmlLayer.style.transform = `translate(${this.camera.x}px, ${this.camera.y}px) scale(${this.camera.zoom})`;
    }

    fitToContent() {
      if (this.devices.length === 0) return;

      let minX = Infinity, minY = Infinity, maxX = -Infinity, maxY = -Infinity;
      this.nodePositions.forEach((pos, id) => {
        minX = Math.min(minX, pos.x);
        minY = Math.min(minY, pos.y);
        maxX = Math.max(maxX, pos.x + this.options.nodeWidth);
        maxY = Math.max(maxY, pos.y + this.options.nodeHeight);
      });

      const padding = 60;
      const contentW = maxX - minX + padding * 2;
      const contentH = maxY - minY + padding * 2;
      const scaleX = this.width / contentW;
      const scaleY = this.height / contentH;
      this.camera.zoom = Math.min(scaleX, scaleY, 1);
      this.camera.x = (this.width - (maxX - minX) * this.camera.zoom) / 2 - minX * this.camera.zoom;
      this.camera.y = (this.height - (maxY - minY) * this.camera.zoom) / 2 - minY * this.camera.zoom;

      this._updateTransform();
      this._renderEdges();
    }

    resetView() {
      this.camera = { x: 0, y: 0, zoom: 1 };
      this._updateTransform();
      this._renderEdges();
    }

    centerOnNode(nodeId) {
      const pos = this.nodePositions.get(nodeId);
      if (!pos) return;
      this.camera.x = this.width / 2 - (pos.x + this.options.nodeWidth / 2) * this.camera.zoom;
      this.camera.y = this.height / 2 - (pos.y + this.options.nodeHeight / 2) * this.camera.zoom;
      this._updateTransform();
      this._renderEdges();
    }

    // ============================================================
    // API PÚBLICA - CALLBACKS
    // ============================================================
    on(event, callback) {
      if (this.callbacks.hasOwnProperty(event)) {
        this.callbacks[event] = callback;
      }
      return this;
    }

    // ============================================================
    // DESTRUCCIÓN
    // ============================================================
    destroy() {
      if (this.animationFrame) cancelAnimationFrame(this.animationFrame);
      this.container.innerHTML = '';
    }

    // ============================================================
    // SERIALIZACIÓN
    // ============================================================
    exportPositions() {
      const result = {};
      this.nodePositions.forEach((pos, id) => {
        result[id] = { x: pos.x, y: pos.y, fixed: pos.fixed };
      });
      return result;
    }

    importPositions(positions) {
      Object.entries(positions).forEach(([id, pos]) => {
        this.nodePositions.set(parseInt(id), { ...pos });
      });
      this.nodeElements.forEach((el, id) => {
        this._updateNodePosition(id);
      });
      this._renderEdges();
    }
  }

  // Exponer globalmente
  global.SagaGraph = SagaGraph;

})(window);
