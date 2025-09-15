<?php

namespace App\Orchid\Screens;

use App\Models\Customer;
use App\Models\InvoiceSale;
use App\Models\Product;
use Orchid\Alert\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Relation;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class InvoiceSaleEditScreen extends Screen
{
    public $invoiceSale;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(InvoiceSale $invoiceSale): array
    {
        $this->invoiceSale = $invoiceSale;

        return [
            'invoice' => $invoiceSale,
            'lines'   =>  $invoiceSale->lines ? $invoiceSale->lines->toArray() : [
                ['product_id' => '', 'qty' => 1, 'price' => 'lines.price', 'subtotal' => 0],
            ],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->invoiceSale->exists ? 'Editar Factura' : 'Nueva Factura';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            Button::make('Imprimir')
                ->icon('printer')
                ->class('btn btn-primary')
                ->method('printInvoice')
                ->canSee($this->invoiceSale->exists),

            Button::make('Guardar')
                ->icon('floppy')
                ->class('btn btn-success')
                ->method('createOrUpdate'),

            Button::make('Eliminar')
                ->icon('trash')
                ->class('btn btn-danger')
                ->method('remove')
                ->canSee($this->invoiceSale->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([

                Group::make([

                    //nit
                    Relation::make('invoice.nit')
                        ->title('NIT')
                        ->fromModel(Customer::class, 'nit')
                        ->required(),

                    Relation::make('invoice.customer_id')
                        ->title('Cliente (por NIT)')
                        ->fromModel(Customer::class, 'nit', 'id')
                        ->displayAppend('name') // Muestra también el nombre al lado del NIT
                        ->required(),

                    Input::make('invoice.status')
                        ->title('Estado')
                        ->value($this->invoiceSale->status ?? 'No-Certificada')
                        ->readonly(),

                    Button::make('Certificar')
                        ->icon('cloud-upload')
                        ->method('certifyInvoice')
                        ->class('float-end')
                        ->canSee($this->invoiceSale->exists),
                ]),

                Group::make([

                DateTimer::make('invoice.date')
                    ->title('Fecha')
                    ->value(now())
                    ->required(),

                Input::make('invoice.serie_fel')
                    ->title('Serie')
                    ->readonly(),

                Input::make('invoice.number_fel')
                    ->title('Número de documento')
                    ->readonly(),

                Input::make('invoice.authorization_number_fel')
                    ->title('Número de documento')
                    ->readonly(),
                ]),

            ]),
            Layout::rows([

                Matrix::make('lines')
                    ->title('Detalle de factura')
                    ->columns([
                        'Producto' => 'product_id',
                        'Cantidad' => 'quantity',
                        'Precio Unitario' => 'unit_price',
                        'Total Línea' => 'total_price',
                    ])
                    ->fields([

                        'product_id' => Relation::make('lines[].product_id')
                            ->fromModel(Product::class, 'name')
                            ->title('Producto')
                            ->required(),

                        'quantity' => Input::make('lines[].quantity')
                            ->type('number')
                            ->required(),

                        'unit_price' => Input::make('lines[].unit_price')
                            ->type('number')
                            ->required(),

                        'total_price' => Input::make('lines[].total_price')
                            ->type('number')
                            ->readonly()
                            ->value(0),
                    ])
            ]),

            Layout::rows([
                Group::make([

                Input::make('invoice.total_amount')
                    ->title('Total')
                    ->type('number')
                    ->readonly(),

                Input::make('invoice.tax')
                    ->title('Impuesto')
                    ->type('number')
                    ->readonly(),

                ]),

            ]),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {

        $factura = $this->invoiceSale->exists
            ? $this->invoiceSale
            : new InvoiceSale();

        $factura->fill($request->get('invoice'));
        $factura->save();

        if ($this->invoiceSale->exists) {
            // Eliminar líneas existentes
            $factura->lines()->delete();
        }
        // Crear nuevas líneas, calculando total_price y sumando total_amount
        $totalAmount = 0;
        foreach ($request->get('lines') as $line) {
            $line['total_price'] = (floatval($line['quantity'] ?? 0)) * (floatval($line['unit_price'] ?? 0));
            $totalAmount += $line['total_price'];
            $factura->lines()->create($line);
        }
        $factura->total_amount = $totalAmount;
        $factura->save();

        Alert::info('Factura guardada correctamente.');

        return redirect()->route('platform.invoice.sale.list');
    }
    public function certifyInvoice()
    {
        //cargamos la informacion de la empresa
        $company = \App\Models\Company::first();
        if (!$company) {
            Alert::error('No se ha configurado la información de la empresa. Por favor, configúrela primero.');
            return;
        }
        $nit = $company->nit;
        $nit_fel = str_pad($company->nit, 12, '0', STR_PAD_LEFT);
        //P1ara certificar factura en Digifact
        $client = new \GuzzleHttp\Client();
        $url = 'https://felgttestaws.digifact.com.gt/felapiv2/api/FelRequest?NIT='.$nit_fel.'&TIPO=CERTIFICATE_DTE_XML_TOSIGN&FORMAT=XML&USERNAME='.$company->user_fel;
        $xml = $this->generarXmlFactura($this->invoiceSale, $company);

        $token = $company->token_fel;

        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/xml'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
        $dataxml = curl_exec($ch);
        if (curl_errno($ch)) {
            print curl_errno($ch);
            echo "  Algo Salio Mal";
        } else {
            curl_close($ch);
        }
        //Procesamos la respuesta
        $json = json_decode($dataxml);

        $this->invoiceSale->status = 'Certificada';
        $this->invoiceSale->serie_fel = $json->Serie;
        $this->invoiceSale->number_fel = $json->NUMERO;
        $this->invoiceSale->authorization_number_fel = $json->Autorizacion;
        $this->invoiceSale->save();

        Alert::info('Factura certificada correctamente.');

        return redirect()->route('platform.invoice.sale.list');
    }

    /**
     * Genera el XML para la factura electrónica según formato SAT
     *
     * @param InvoiceSale $factura
     * @param \App\Models\Company $company
     * @return string
     */
    private function generarXmlFactura(InvoiceSale $factura, $company): string
    {
        // Obtener cliente
        $cliente = Customer::find($factura->customer_id);

        // Formato de fecha requerido
        $fechaEmision = date('Y-m-d\TH:i:s', strtotime($factura->date));

        // Calcular totales e impuestos
        $totalImpuesto = $factura->tax ?? ($factura->total_amount / 1.12) * 0.12;
        $montoGravable = $factura->total_amount - $totalImpuesto;

        // Iniciar construcción del XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <dte:GTDocumento xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="0.1">
              <dte:SAT ClaseDocumento="dte">
                <dte:DTE ID="DatosCertificados">
                  <dte:DatosEmision ID="DatosEmision">
                    <dte:DatosGenerales Tipo="FACT" FechaHoraEmision="' . $fechaEmision . '" CodigoMoneda="GTQ"/>
                    <dte:Emisor NITEmisor="' . $company->nit . '" NombreEmisor="' . $company->name . '" CodigoEstablecimiento="1" NombreComercial="' . $company->name . '" AfiliacionIVA="GEN">
                      <dte:DireccionEmisor>
                        <dte:Direccion>' . $company->address . '</dte:Direccion>
                        <dte:CodigoPostal>' . $company->zip . '</dte:CodigoPostal>
                        <dte:Municipio>' . $company->city . '</dte:Municipio>
                        <dte:Departamento>' . $company->state . '</dte:Departamento>
                        <dte:Pais>GT</dte:Pais>
                      </dte:DireccionEmisor>
                    </dte:Emisor>
                    <dte:Receptor NombreReceptor="' . $cliente->name . '" IDReceptor="' . $cliente->nit . '">
                      <dte:DireccionReceptor>
                        <dte:Direccion>' . ($cliente->address ?? 'GUATEMALA') . '</dte:Direccion>
                        <dte:CodigoPostal>' . ($cliente->postal_code ?? '01010') . '</dte:CodigoPostal>
                        <dte:Municipio>' . ($cliente->municipality ?? 'GUATEMALA') . '</dte:Municipio>
                        <dte:Departamento>' . ($cliente->department ?? 'GUATEMALA') . '</dte:Departamento>
                        <dte:Pais>GT</dte:Pais>
                      </dte:DireccionReceptor>
                    </dte:Receptor>
                    <dte:Frases>
                      <dte:Frase TipoFrase="1" CodigoEscenario="1"/>
                    </dte:Frases>
                    <dte:Items>';

                    // Agregar cada línea de la factura
                    $numeroLinea = 1;
                    foreach ($factura->lines as $linea) {
                        $producto = Product::find($linea->product_id);
                        $precioTotal = $linea->quantity * $linea->unit_price;
                        $montoGravableLinea = $precioTotal / 1.12;
                        $impuestoLinea = $precioTotal - $montoGravableLinea;

                        $xml .= '
                      <dte:Item NumeroLinea="' . $numeroLinea . '" BienOServicio="B">
                        <dte:Cantidad>' . $linea->quantity . '</dte:Cantidad>
                        <dte:UnidadMedida>und</dte:UnidadMedida>
                        <dte:Descripcion>' . $producto->name . '</dte:Descripcion>
                        <dte:PrecioUnitario>' . $linea->unit_price . '</dte:PrecioUnitario>
                        <dte:Precio>' . $precioTotal . '</dte:Precio>
                        <dte:Descuento>0</dte:Descuento>
                        <dte:Impuestos>
                          <dte:Impuesto>
                            <dte:NombreCorto>IVA</dte:NombreCorto>
                            <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>
                            <dte:MontoGravable>' . number_format($montoGravableLinea, 6, '.', '') . '</dte:MontoGravable>
                            <dte:MontoImpuesto>' . number_format($impuestoLinea, 6, '.', '') . '</dte:MontoImpuesto>
                          </dte:Impuesto>
                        </dte:Impuestos>
                        <dte:Total>' . $precioTotal . '</dte:Total>
                      </dte:Item>';

                        $numeroLinea++;
                    }

                    // Cerrar el XML con los totales
                    $xml .= '
                    </dte:Items>
                    <dte:Totales>
                      <dte:TotalImpuestos>
                        <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="' . number_format($totalImpuesto, 6, '.', '') . '"/>
                      </dte:TotalImpuestos>
                      <dte:GranTotal>' . $factura->total_amount . '</dte:GranTotal>
                    </dte:Totales>
                  </dte:DatosEmision>
                </dte:DTE>
              </dte:SAT>
        </dte:GTDocumento>';

        return $xml;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove()
    {
        $this->invoiceSale->delete();

        Alert::info('Factura eliminada de forma correcta.');

        return redirect()->route('platform.invoice.sale.list');
    }

    public function printInvoice()
    {
        Alert::info('Funcionalidad de impresión no implementada aún.');
    }
}
