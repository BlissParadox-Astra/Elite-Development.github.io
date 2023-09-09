<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockInRequest;
use Illuminate\Http\Request;
use App\Managers\StockInManager;

class StockInController extends Controller
{
    protected $stockInManager;

    public function __construct(StockInManager $stockInManager)
    {
        $this->stockInManager = $stockInManager;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $stockIns = $this->stockInManager->getAllStockIns();

            return response()->json(['stock_ins' => $stockIns]);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StockInRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Create the stock-in record using the manager
            $this->stockInManager->createStockIn($validatedData);

            return response()->json(['message' => 'Stock-In record created successfully']);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            return response()->json(['error' => $errorMessage], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
