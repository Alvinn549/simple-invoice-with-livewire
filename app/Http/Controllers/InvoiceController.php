<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class InvoiceController extends Controller
{
    public function index() {
        return view('index');
    }

   public function printInvoice(Request $request)
   {
        $name = $request->get('name');
        $date = $request->get('date');
        $noInvoice = $request->get('noInvoice');
        $products = $request->get('products');
        $allTotal = $request->get('allTotal');

        $data = compact('name','date', 'noInvoice', 'products', 'allTotal');
        // dd($data);

        return view('print', $data);
    }

}
