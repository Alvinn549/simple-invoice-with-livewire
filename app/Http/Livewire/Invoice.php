<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon; 
use Illuminate\Support\Str;

class Invoice extends Component
{
    public $name;
    public $date;
    public $noInvoice;
    public $products = [];
    public $allTotal = 0;
    public $diskonType;
    public $diskonInPercent;
    public $diskonInNominal;

       protected $rules = [
        'name' => 'required|string',
        'products' => 'required',
        'products.*.deskripsi' => 'required|string',
         'diskonInPercent' => 'nullable|numeric|min:0|max:100',
    ];

    protected $messages = [
        'products.*.deskripsi.required' => 'The Deskripsi field is required.',
        'products.*.deskripsi.string' => 'The Deskripsi field for each product must be a string.',
    ];

    public function mount()
    {
        $this->date = Carbon::now()->format('d/m/Y');
        $this->noInvoice = Str::random(8);
    }

    public function addRow()
    {
        if ($this->products == []) {
            $this->resetDiskonType();
        }

        $this->resetValidation();
        $nextNumber = count($this->products) + 1;

        $this->products[] = [
            'no' => $nextNumber, 
            'deskripsi' => null, 
            'quantity' => null, 
            'harga_satuan' => null
        ];
    }

    public function removeRow($key)
    {
        unset($this->products[$key]);
        $this->products = array_values($this->products);

        foreach ($this->products as $index => $product) {
            $this->products[$index]['no'] = $index + 1;
        }

        $this->getOverallTotal(); 
    }

    public function checkHargaSatuan($key)
    {
        $this->changeFormatNumberHargaSatuan($key);
        $this->getTotal($key);
    }

    public function checkQuantity($key)
    {
        $this->changeFormatNumberQuantity($key);
        $this->getTotal($key);
    }

    public function changeFormatNumberQuantity($key)
    {
        $this->products[$key]['quantity'] = number_format((int) str_replace(',', '', $this->products[$key]['quantity']), 0, '.', ',');
    }

    public function changeFormatNumberHargaSatuan($key)
    {
        $this->products[$key]['harga_satuan'] = number_format((int) str_replace(',', '', $this->products[$key]['harga_satuan']), 0, '.', ',');
    }


    public function getTotal($key)
    {
        $quantity = (int) str_replace(',', '', $this->products[$key]['quantity']);
        $hargaSatuan = (int) str_replace(',', '', $this->products[$key]['harga_satuan']);
        
        if (is_numeric($quantity) && is_numeric($hargaSatuan)) {
            $total = $quantity * $hargaSatuan;
            $this->products[$key]['total'] = $total;
        } else {
            $this->products[$key]['total'] = 0;
        }

        $this->getOverallTotal(); 
    }

    public function getOverallTotal()
    {
        $subtotal = array_sum(array_column($this->products, 'total'));

        if ($this->diskonType === 'percent') {
            $discountAmount = $this->diskonInPercent ? ($subtotal * ($this->diskonInPercent / 100)) : 0;
        } elseif ($this->diskonType === 'nominal') {
            $discountAmount = $this->diskonInNominal ? $this->diskonInNominal : 0;
        } else {
            $discountAmount = 0;
        }

        $this->allTotal = $subtotal - $discountAmount;
    }

    
    
    public function print()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'date' => $this->date,
            'noInvoice' => $this->noInvoice,
            'products' => $this->products,
            'allTotal' => $this->allTotal,
        ];

        return redirect()->route('print.invoice', $data);
    }

    public function resetDiskonType()
    {
        if ($this->diskonType === 'percent') {
             $this->reset('diskonInNominal');
        } elseif ($this->diskonType === 'nominal') {
             $this->reset('diskonInPercent');
        } else {
            $this->reset([
            'diskonInNominal',
            'diskonInPercent',
            ]);
        }
        $this->getOverallTotal();
    }


    public function render()
    {
        return view('livewire.invoice');
    }
}
