<?php

namespace App\Http\Controllers\API;

use App\Models\Voucher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\VoucherResource;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view-vouchers')->only('index');
        $this->middleware('can:create-vouchers')->only('store');
        $this->middleware('can:delete-vouchers')->only('destroy');
    }

    public function index()
    {
        VoucherResource::withoutWrapping();

        return VoucherResource::collection(auth()->user()->vouchers()->paginate(5));
    }

    public function store(Request $request)
    {
        if(Voucher::query()->where('user_id', auth()->id())->count() == 10) {
            return response()->json(['message' => 'max 10 vouchers per user.'], 401);
        }

        do {
            $code = Str::uuid();
        } while (Voucher::query()->where('code', $code)->exists());

        Voucher::create([
            'user_id' => auth()->id(),
            'code' => $code,
        ]);

        return response()->json(['message' => 'success']);
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return response()->json(['message' => 'success']);
    }
}
