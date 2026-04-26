<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; padding: 0 20px; }

        .header { padding: 12px 0; border-bottom: 3px solid #1e3a5f; display: flex; justify-content: space-between; align-items: center; }
        .brand { font-size: 17px; font-weight: bold; color: #1e3a5f; text-transform: uppercase; letter-spacing: 1px; }
        .brand span { color: #f97316; font-size: 20px; letter-spacing: 2px; }
        .brand-sub { font-size: 9px; color: #6b7280; margin-top: 2px; }

        .servicios { display: table; width: 100%; border-bottom: 2px solid #1e3a5f; border-top: 1px solid #e5e7eb; }
        .servicios-row { display: table-row; }
        .servicio { display: table-cell; text-align: center; padding: 6px 4px; font-size: 9px; font-weight: bold; color: #1e3a5f; border-right: 1px solid #e5e7eb; text-transform: uppercase; width: 20%; }
        .servicio:last-child { border-right: none; }

        .oficina-title { background-color: #1e3a5f; color: white; text-align: center; padding: 4px; font-size: 10px; font-weight: bold; letter-spacing: 1px; }

        .info-grid { display: table; width: 100%; border-bottom: 1px solid #e5e7eb; }
        .info-left { display: table-cell; width: 60%; padding: 8px 12px; border-right: 1px solid #e5e7eb; font-size: 10px; line-height: 1.6; vertical-align: top; }
        .info-right { display: table-cell; width: 40%; padding: 8px 12px; font-size: 10px; line-height: 1.6; vertical-align: top; }
        .info-label { color: #6b7280; }
        .info-value { font-weight: bold; }

        .saludo { padding: 6px 12px; font-size: 10px; color: #4b5563; border-bottom: 1px solid #e5e7eb; font-style: italic; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #1e3a5f; color: white; }
        thead th { padding: 6px 8px; text-align: left; font-size: 10px; }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }
        tbody tr { border-bottom: 1px solid #f3f4f6; }
        tbody tr:nth-child(even) { background-color: #f9fafb; }
        tbody td { padding: 6px 8px; font-size: 10px; }
        tbody td.right { text-align: right; }
        tbody td.center { text-align: center; }

        .footer-table { display: table; width: 100%; margin-top: 8px; }
        .footer-left { display: table-cell; width: 60%; vertical-align: bottom; }
        .footer-right { display: table-cell; width: 40%; vertical-align: bottom; }
        .condiciones { font-size: 9px; color: #4b5563; }
        .condiciones p { margin-bottom: 2px; }
        .totales { width: 200px; }
        .totales table { width: 100%; }
        .totales td { padding: 3px 8px; font-size: 10px; border: 1px solid #e5e7eb; }
        .totales td.label { background-color: #1e3a5f; color: white; font-weight: bold; }
        .totales td.value { text-align: right; font-weight: bold; }
        .totales tr.total-row td { background-color: #f97316; color: white; font-size: 12px; }

        .contact-footer { margin-top: 10px; border-top: 2px solid #1e3a5f; padding-top: 6px; font-size: 9px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>

    {{-- Header con logo --}}
    <div class="header">
        <div style="display:flex; align-items:center; gap:12px;">
            @if(file_exists(public_path('images/logo.png')))
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" style="width:60px; height:60px; object-fit:contain; border-radius:50%;">
            @endif
            <div>
                <div class="brand">Mangueras y Conexiones <span>MAE</span></div>
                <div class="brand-sub">Hidráulicas y Neumáticas — Soluciones Industriales desde 2015</div>
                <div class="brand-sub">RFC: MOME8902246H8 | Benito Juárez 33-29, Habitacional, 54038 Tlalnepantla, Méx.</div>
                <div class="brand-sub">Tel: 55 4230 5373 | Oficina: 55 5317 7681 | conexiones.mangueras@hotmail.com</div>
                <div class="brand-sub">Facebook: facebook.com/profile.php?id=61563654431552</div>
            </div>
        </div>
        <div style="text-align:right;">
            <div style="font-size:9px; color:#6b7280; text-transform:uppercase; letter-spacing:1px;">Cotización</div>
            <div style="font-size:20px; font-weight:bold; color:#f97316;">{{ $quote->folio }}</div>
            <div style="font-size:9px; color:#6b7280;">{{ $quote->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- Servicios --}}
    <div class="servicios">
        <div class="servicios-row">
            <div class="servicio">Neumática</div>
            <div class="servicio">Manómetros</div>
            <div class="servicio">Hidráulica</div>
            <div class="servicio">Conexiones</div>
            <div class="servicio">Reparación de Cilindros</div>
        </div>
    </div>

    {{-- Datos oficina y cliente --}}
    <div class="oficina-title">DATOS DE CONTACTO</div>
    <div class="info-grid">
        <div class="info-left">
            <div><span class="info-label">Nombre: </span><span class="info-value">{{ $quote->customer->name }}</span></div>
            @if($quote->customer->company)
            <div><span class="info-label">Empresa: </span><span class="info-value">{{ $quote->customer->company }}</span></div>
            @endif
            @if($quote->customer->rfc)
            <div><span class="info-label">RFC: </span><span class="info-value">{{ $quote->customer->rfc }}</span></div>
            @endif
            @if($quote->customer->address)
            <div><span class="info-label">Dirección: </span>{{ $quote->customer->address }}</div>
            @endif
            @if($quote->customer->city)
            <div><span class="info-label">Ciudad: </span>{{ $quote->customer->city }}{{ $quote->customer->zip_code ? ' CP.' . $quote->customer->zip_code : '' }}</div>
            @endif
        </div>
        <div class="info-right">
            <div><span class="info-label">Tel: </span><span class="info-value">{{ $quote->customer->phone }}</span></div>
            @if($quote->customer->email)
            <div><span class="info-label">Email: </span>{{ $quote->customer->email }}</div>
            @endif
            <div style="margin-top:6px;"><span class="info-label">Fecha: </span><span class="info-value">{{ $quote->created_at->format('d/m/Y') }}</span></div>
            <div><span class="info-label">Núm. Cotización: </span><span class="info-value">{{ $quote->folio }}</span></div>
            <div style="margin-top:4px;">
                <span style="padding:2px 8px; border-radius:4px; font-size:9px; font-weight:bold;
                    {{ $quote->status === 'confirmada' ? 'background:#dcfce7; color:#166534;' : '' }}
                    {{ $quote->status === 'pendiente' ? 'background:#fef9c3; color:#854d0e;' : '' }}
                    {{ $quote->status === 'cancelada' ? 'background:#fee2e2; color:#991b1b;' : '' }}
                ">{{ strtoupper($quote->status) }}</span>
            </div>
        </div>
    </div>

    <div class="saludo">Agradeciendo la oportunidad que nos brinda, nos es grato presentar la siguiente cotización:</div>

    {{-- Tabla de productos --}}
    <table>
        <thead>
            <tr>
                <th class="center" style="width:40px;">#</th>
                <th class="center" style="width:50px;">Cant.</th>
                <th class="center" style="width:50px;">Unidad</th>
                <th>Descripción</th>
                <th class="right" style="width:80px;">P. Unit.</th>
                <th class="right" style="width:80px;">Total</th>
                <th class="center" style="width:70px;">T. Entrega</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $i => $item)
            <tr>
                <td class="center" style="color:#9ca3af;">{{ $i + 1 }}</td>
                <td class="center">{{ $item->quantity }}</td>
                <td class="center">{{ $item->product->unit }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="right">${{ number_format($item->unit_price, 2) }}</td>
                <td class="right">${{ number_format($item->subtotal, 2) }}</td>
                <td class="center">{{ $item->delivery_time ?? '—' }}</td>
            </tr>
            @endforeach
            {{-- Filas vacías para dar espacio --}}
            @for($i = count($quote->items); $i < 8; $i++)
            <tr style="height:18px;"><td colspan="7"></td></tr>
            @endfor
        </tbody>
    </table>

    {{-- Footer con condiciones y totales --}}
    @php
        $subtotal = $quote->items->sum('subtotal');
        $iva = $subtotal * 0.16;
        $total = $subtotal + $iva;
    @endphp

    <div class="footer-table">
        <div class="footer-left">
            <div class="condiciones">
                <p><strong>CONDICIONES COMERCIALES:</strong> {{ $quote->conditions ?? 'Crédito 30 días' }}</p>
                <p>PRECIOS EN PESOS MEXICANOS</p>
                <p>VIGENCIA COTIZACIÓN: 15 DÍAS</p>
                @if($quote->notes)
                <p style="margin-top:4px;"><strong>NOTAS:</strong> {{ $quote->notes }}</p>
                @endif
            </div>
        </div>
        <div class="footer-right">
            <div class="totales">
                <table>
                    <tr>
                        <td class="label">SUBTOTAL</td>
                        <td class="value">${{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="label">IVA 16%</td>
                        <td class="value">${{ number_format($iva, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="label">TOTAL</td>
                        <td class="value">${{ number_format($total, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="contact-footer">
        Mangueras y Conexiones MAE &nbsp;|&nbsp; RFC: MOME8902246H8 &nbsp;|&nbsp; Benito Juárez 33-29, Habitacional, 54038 Tlalnepantla, Méx. &nbsp;|&nbsp; Tel: 55 4230 5373 &nbsp;|&nbsp; conexiones.mangueras@hotmail.com &nbsp;|&nbsp; Facebook: facebook.com/profile.php?id=61563654431552
    </div>

</body>
</html>
