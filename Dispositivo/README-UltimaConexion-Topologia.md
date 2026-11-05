# Campo «Última conexión» en la topología de dispositivos

**Módulo:** `Dispositivo`
**Vista:** `Dispositivo/DispositivoTopologia.php`
**Librería:** `public_html/assets/js/sagagraph.js` (SagaGraph)
**Versión del documento:** 1.0.0
**Fecha:** 2026-10-30

---

## 1. Objetivo

Mostrar el campo `UltimaConexion` de la tabla `Dispositivo` dentro de la **card de cada nodo** en la vista de topología (SagaGraph), además de dejarlo disponible en el tooltip.

El campo ya existe en la base de datos y ya se actualiza automáticamente al guardar un dispositivo con estatus activo:

```php
// Dispositivo/Dispositivo.php — DispositivoActualizar()
`UltimaConexion` = IF(%s = 1, NOW(), `UltimaConexion`)
```

Por lo tanto **no hay cambios de esquema ni migraciones**: solo hay que transportar el dato hasta la vista y pintarlo.

---

## 2. Punto clave: dónde vive el HTML de la card

El HTML de las cards **no se genera en `DispositivoTopologia.php`**, sino en la librería:

| Archivo | Responsabilidad |
| --- | --- |
| `public_html/assets/js/sagagraph.js` | Genera el markup de la card (`_createCard`) y del tooltip (`_defaultTooltipContent`) |
| `Dispositivo/Dispositivo.php` | Consulta SQL y formatea el arreglo que consume la librería (`DispositivoTopologia`, `DispositivoFormatearSagaGraph`) |
| `Dispositivo/DispositivoTopologia.php` | Serializa el arreglo a JSON e instancia `new SagaGraph(...)` con sus opciones de layout |

Consecuencia: **no es posible agregar el campo tocando únicamente el PHP de la vista**. Se requieren los tres cambios de la sección 3.

### Flujo del dato

```
Dispositivo.UltimaConexion (MySQL)
        │  SELECT d.UltimaConexion AS ultima_conexion
        ▼
DispositivoFormatearSagaGraph()  →  'lastConnection' => 'd/m/Y H:i'
        │
        ▼
$dispositivosJson  (JSON embebido en la vista)
        │
        ▼
new SagaGraph(...).setData(...).layout()
        │
        ▼
_createCard(device)  →  fila «Última conexión» en la card
```

---

## 3. Cambios a realizar

### 3.1 `Dispositivo/Dispositivo.php` — traer y formatear el dato

#### a) Consulta de `DispositivoTopologia()` (aprox. línea 403)

Agregar la columna junto a `d.MAC AS mac`:

```sql
d.UltimaConexion AS ultima_conexion,
```

Resultado del bloque `SELECT`:

```php
$Query  = 'SELECT
            d.id,
            d.Nombre AS name,
            d.IP AS ip,
            d.MAC AS mac,
            d.UltimaConexion AS ultima_conexion,
            d.Estatus AS status_raw,
            ...
```

#### b) `DispositivoFormatearSagaGraph()` (aprox. línea 475)

Agregar la llave al arreglo de retorno:

```php
return array(
    'id'       => (int) $Row['id'],
    'parents'  => $parents,
    'credentials' => $credentials,
    'name'     => $Row['name'],
    'type'     => $tipoSaga,
    'status'   => $estado,
    'ip'       => !empty($Row['ip'])     ? $Row['ip']     : 'N/A',
    'mac'      => !empty($Row['mac'])    ? $Row['mac']    : 'N/A',
    'location' => !empty($Row['location'])? $Row['location']: 'Sin ubicación',
    'lastConnection' => !empty($Row['ultima_conexion'])
        ? date('d/m/Y H:i', strtotime($Row['ultima_conexion']))
        : 'Sin registro',
    'parentId' => count($parents) ? $parents[0] : NULL,
);
```

> **Importante:** `DispositivoFormatearSagaGraph()` es compartido por tres funciones:
> `DispositivoTopologia()`, `DispositivoObtenerPorId()` y `DispositivoObtenerHijos()`.
> Si solo se agrega la columna en la primera, las otras dos devolverán `Sin registro`.
> Dos opciones válidas:
>
> 1. Agregar `d.UltimaConexion AS ultima_conexion,` también en esas dos consultas (recomendado, consistencia total).
> 2. Dejar solo la de topología y confiar en el guard `!empty($Row['ultima_conexion'])`, que ya evita `Undefined index`.
>
> Si el proyecto corre con `error_reporting` estricto, la opción 1 evita cualquier aviso.

**Formato de salida:** `30/10/2026 14:35` (24 h). Si se prefiere formato ISO o relativo («hace 2 h»), cambiar aquí y no en JS, para no repetir lógica en el frontend.

---

### 3.2 `public_html/assets/js/sagagraph.js` — pintar el campo

#### a) Card: `_createCard()` (aprox. líneas 512-528)

Se reemplaza la fila «Ubicación» (que actualmente es la última y no tiene margen inferior) por el par Ubicación + Última conexión:

```js
<div style="display:flex;justify-content:space-between;margin-bottom:3px;">
  <span style="color:#94a3b8;">Ubicación</span>
  <span style="font-weight:500;color:#334155;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-align:right;">${this._escapeHtml(device.location || 'N/A')}</span>
</div>
${device.lastConnection ? `
<div style="display:flex;justify-content:space-between;">
  <span style="color:#94a3b8;">Última conexión</span>
  <span style="font-weight:500;color:#334155;font-size:10px;white-space:nowrap;">${this._escapeHtml(device.lastConnection)}</span>
</div>` : ''}
```

Decisiones de diseño:

- **Guard `device.lastConnection`**: si el campo no viene (otro consumidor de la librería, dato nulo), la card se renderiza igual que antes. La librería es genérica y no debe asumir el modelo de datos de este módulo.
- **`_escapeHtml()`**: se usa igual que el resto de los campos para no romper el markup con caracteres especiales.
- **`white-space:nowrap`** y `font-size:10px`: la fecha completa cabe en los 240 px de ancho de nodo sin partirse en dos líneas (lo que descuadraría el alto).

#### b) Tooltip: `_defaultTooltipContent()` (aprox. línea 787)

Para que el tooltip no quede inconsistente con la card, agregar la fila (después de «MAC», antes de «Ubicación»):

```js
<div style="display:flex;justify-content:space-between;padding:3px 0;border-bottom:1px solid rgba(255,255,255,0.08);">
  <span style="color:#94a3b8;">Última conexión</span><span>${this._escapeHtml(device.lastConnection || 'N/A')}</span>
</div>
```

#### c) Encabezado de la librería

Subir `@version 1.0.0` → `1.0.1` en el comentario inicial (línea 6).

---

### 3.3 `Dispositivo/DispositivoTopologia.php` — ajustar `nodeHeight`

**Este cambio es obligatorio.** La card tiene alto automático, pero el layout y las flechas usan `nodeHeight` como valor fijo:

| Uso en `sagagraph.js` | Efecto si `nodeHeight` es menor al alto real |
| --- | --- |
| `_layoutNode()` (línea ~449) | Los hijos se posicionan encima de la card padre |
| `_renderEdges()` (línea ~690) | La flecha nace **dentro** de la card |
| `fitToContent()` (línea ~907) | El encuadre recorta la parte inferior |
| `centerOnNode()` (línea ~933) | El centrado queda desplazado |

En las opciones del constructor (línea ~166):

```js
nodeHeight: 190,   →   nodeHeight: 212,
```

La fila nueva suma ~19-20 px (10 px de línea + 3 px de margen + 6 px de padding heredado). Si se prefiere margen de seguridad, usar `215`.

**Nada más cambia en este archivo:** `$dispositivosJson` serializa el arreglo completo, así que `lastConnection` llega solo a la card.

---

## 4. Alternativas evaluadas y descartadas

| Alternativa | Por qué se descartó |
| --- | --- |
| **Subclase** `class MiGraph extends SagaGraph { _createCard(d) { ... } }` | No requiere tocar la librería, pero duplica lógica de la card y se rompe en silencio si la librería cambia. |
| **Post-proceso del DOM** tras `layout()` | Frágil: cada `setData().layout()` (buscador y filtros, líneas ~199-240) reconstruye todas las cards y se pierde la inyección. |
| **`onTooltipRender`** | Solo aplica al tooltip, no al HTML de la card. |
| **Editar el JSON desde la vista** | El dato ya viene del controlador; duplicar el formateo en la vista rompe el principio de una sola fuente. |

Se eligió el **guard opcional dentro de la librería**: es un cambio de ~10 líneas, retrocompatible y centralizado.

---

## 5. Verificación

### Checklist funcional

- [ ] La card muestra «Última conexión» con fecha y hora para dispositivos con registro.
- [ ] La card muestra `Sin registro` para dispositivos con `UltimaConexion = NULL`.
- [ ] El tooltip muestra el mismo valor.
- [ ] Las flechas (edges) nacen en el borde inferior de la card, no dentro.
- [ ] «Ajustar vista» (`fitToContent`) no recorta la card inferior.
- [ ] «Reorganizar» (`layout`) mantiene separación vertical correcta entre niveles.
- [ ] Guardar un dispositivo como activo actualiza la fecha y, al recargar, se refleja en la card.
- [ ] El buscador y los filtros de tipo/estado siguen funcionando (llaman a `setData().layout()`).
- [ ] Otras vistas que usen SagaGraph (si existen) siguen renderizando sin la fila nueva.

### Prueba de la consulta sin pasar por la interfaz

```sql
SELECT d.id, d.Nombre, d.UltimaConexion
FROM Dispositivo d
ORDER BY d.id ASC
LIMIT 20;
```

Si hay filas con `NULL`, esas cards deben mostrar `Sin registro`.

---

## 6. Notas y consideraciones

- **Zona horaria:** `UltimaConexion` se guarda con `NOW()` (hora del servidor MySQL) y se formatea con `date()` (zona horaria de PHP). Si el proyecto tiene configurada `CelaZonaHoraria`, conviene formatear con esa zona para que la hora mostrada coincida con la del usuario. No es un requisito de este cambio, pero es la causa típica de «la fecha está corrida».
- **Búsqueda:** si se quiere que el buscador encuentre por fecha, agregar en `buscarDispositivo()` (línea ~204):
  ```js
  (d.lastConnection && d.lastConnection.toLowerCase().indexOf(q) !== -1)
  ```
- **Ancho del nodo:** si en el futuro el formato de fecha crece (por ejemplo, se agrega zona horaria), revisar `nodeWidth: 240` antes de que el texto se desborde.
- **Rollback:** revertir los tres bloques (columna SQL, llave `lastConnection`, fila en `_createCard`) y volver `nodeHeight` a `190`. No hay cambios en base de datos, por lo que el rollback es puramente de código.

---

## 7. Resumen de archivos modificados

| Archivo | Tipo de cambio | Líneas aprox. |
| --- | --- | --- |
| `Dispositivo/Dispositivo.php` | `+ d.UltimaConexion AS ultima_conexion` en el SELECT | 411 |
| `Dispositivo/Dispositivo.php` | `+ 'lastConnection'` en `DispositivoFormatearSagaGraph()` | 485 |
| `public_html/assets/js/sagagraph.js` | Fila nueva en `_createCard()` | 524 |
| `public_html/assets/js/sagagraph.js` | Fila nueva en `_defaultTooltipContent()` | 787 |
| `public_html/assets/js/sagagraph.js` | `@version` 1.0.0 → 1.0.1 | 6 |
| `Dispositivo/DispositivoTopologia.php` | `nodeHeight` 190 → 212 | 166 |
