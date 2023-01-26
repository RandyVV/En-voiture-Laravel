<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Cars::all();
        if (count($cars) <= 0) {
            return response(["message" => "aucune voiture de disponible"], 200);
        }
        return response($cars, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carsValidation = $request->validate([
            "model" => ["required", "string"],
            "price" => ["required", "numeric"],
            "description" => ["required", "string", "min:5"],
            "user_id" => ["required", "numeric"],
        ]);

        $cars = Cars::create([
            "model" => $carsValidation["model"],
            "price" => $carsValidation["price"],
            "description" => $carsValidation["description"],
            "user_id" => $carsValidation["user_id"],
        ]);
        return response(["message" => "voiture ajoutée"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = DB::table("cars")
            ->join("users", "cars.user_id", "=", "users.id")
            ->select("cars.*", "users.name", "users.email")
            ->where("cars.id", "=", $id)
            ->get()
            ->first();
        return $car;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carsValidation = $request->validate([
            "model" => ["string"],
            "price" => ["numeric"],
            "description" => ["string", "min:5"],
            "user_id" => ["required", "numeric"],
        ]);
        $car = Cars::find($id);
        if (!$car) {
            return response(["message" => "aucune voiture de trouvée avec cet id $id"], 404);
        }
        if ($car->user_id != $carsValidation["user_id"]) {
            return response(["message" => "action interdite"], 403);
        }
        $car->update($carsValidation);
        return response(["messafe" => "voiture mise à jour"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cars  $cars
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $carsValidation = $request->validate([
            "user_id" => ["required", "numeric"],
        ]);
        $car = Cars::find($id);
        if (!$car) {
            return response(["message" => "aucune voiture de trouvée avec cet id $id"], 404);
        }
        if ($car->user_id != $carsValidation["user_id"]) {
            return response(["message" => "action interdite"], 403);
        }
        $value = Cars::destroy(($id));
        if(boolval($value) === false) {
            return response(["message" => "aucune voiture de trouvée avec cet $id"], 404);
        }
        return response(["message" => "voiture supprimée"], 200);
    }
}
