<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductionSetting;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['pagetitle'] = 'List of Products';
        $data['products'] = Product::with('team')->get();

        return view('products.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user == null) {
            Auth::logout();
session()->flush();  // Clears all session data
return redirect('/');
        }
        $teams = Team::all();
        $data['pagetitle'] = 'Create New Product';
        $data['teams'] = $teams;

        return view('products.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required',
                'price'=>'required',
                'team' => 'required',
            ]);


            if ($validator->fails()) {
                throw new \Exception("Validation failed");
            }

            $product = new Product();
            $product->name = $request->name;
            $product->code = $request->code;
            $product->price =$request->price;
            $product->team_id = $request->team;
            $product->save();

            $production_setting = new ProductionSetting();
            $production_setting->product_id = $product->id;
            $production_setting->no_of_phases = 5;
            $production_setting->save();

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            if ($e instanceof QueryException) {
                return redirect()->back()->withInput()->with('error', 'Database error occurred.');
            }
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $teams = Team::all();

        $data['pagetitle'] = 'Edit Product';
        $data['product'] = $product;
        $data['teams'] = $teams;

        return view('products.edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'code' => 'required',
                'price' => 'required',
                'team' => 'required',
            ]);

            $product->name = $request->name;
            $product->code = $request->code;
            $product->price = $request->price;
            $product->team_id = $request->team;

            $product->update();

            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput()->with('error', 'Validation error occurred.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
