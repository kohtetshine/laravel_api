<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\V1\InvoiceFilter;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\BulkStoreInvoiceRequest;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Resources\V1\InvoiceResource;
use Illuminate\Support\Arr;


class InvoiceController extends Controller
{
    public function index(Request $request){
        $filter = new InvoiceFilter();
        $filterItems = $filter->transform($request);

        if(count($filterItems) == 0){
            return new InvoiceCollection(Invoice::paginate());
        }else{
            $invoices = Invoice::where($filterItems)->paginate();
            return new InvoiceCollection($invoices->appends($request->query()));
        }

    }
    public function show(Invoice $invoice){
        return new InvoiceResource($invoice);
    }
    
    public function bulkStore(BulkStoreInvoiceRequest $request){
        $bulk = collect($request->all())->map(function($arr,$key){
            return Arr::except($arr,['customerId','billedDate','paidDate']);
        });
        Invoice::insert($bulk->toArray());
    }
}
