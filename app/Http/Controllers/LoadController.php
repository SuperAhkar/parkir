<?php

namespace App\Http\Controllers;

use App\Models\RegParkir;
use App\Models\reservasi;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoadController extends Controller
{
    function logout(){
        Auth::logout();
        return redirect('/');
    }
    
    function updateprofilepage()
    {
        $user = User::find(Auth::user()->id);
        return view('user.info')->with('user', $user);
    }
    function updateprofilepengelola()
    {
        $user = User::find(Auth::user()->id);
        return view('pengelola.profile')->with('user', $user);
    }
    function regtempatparkir()
    {
        $parkir = RegParkir::find(Auth::user()->id);

        return view('pengelola.regparkir')->with('parkir', $parkir);
    }
    function pengelolainfo()
    {
        $parkir = RegParkir::where('user_id', Auth::user()->id)->get();
        return view('pengelola.info')->with('parkir', $parkir);
    }
    function getparkir()
    {
        $parkir = RegParkir::all();
        return view('map.map')->with('parkir', $parkir);
    }

    function usergethome()
    {
        $home = RegParkir::all();

        return view('user.homepage')->with('home', $home);
    }

    function getsearchparkir(Request $request)
    {
        $search  = $request->search == null ? '' : $request->search;

        $parkir = RegParkir::where('name', 'like', '%' . $search . '%')
        ->orWhere('lokasi', 'like', '%' . $search . '%')->get();

        return view('map.searchmap')->with('parkir', $parkir);
    }

    function parkirdetail($id)
    {
        $data = RegParkir::find($id);
        return view('map.detail', compact('data', 'id'));
    }

    function doreservasi($id)
    {
        $data = RegParkir::find($id);

        // dd($data);

        return view('map.reservasi', compact('data', 'id'));
    }

    function getdashboardpengelola()
    {
        $reserve = DB::table('reservasis');

        $user = User::find(Auth::user()->id);
        $parkir = reservasi::where('parkir_id', '=', Auth::user()->id)->get();


        // $parkir = reservasi::all()->where('parkir_id', Auth::user()->id);

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.slot', 'reg_parkirs.slotmaksimal')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->where('parkir_id', '=', Auth::user()->id)
            ->get();

        //   dd($data);

        return view('pengelola.dashboard', ['reservasis' => $data]);
    }

    function getrekappengelola()
    {
        $reserve = DB::table('reservasis');
        $bulanawal = date('Y-m');
        $bulanakhir = "".date('Y')."-12"; 
        $tahunawal = date('Y'); 
        $tahunakhir = date('Y'); 

        $months = reservasi::select(DB::raw('DISTINCT MONTHNAME(checkindate) bulan'))
        ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
        ->whereRaw("MONTH(checkindate) >= ".date('m')."")
        ->whereRaw("MONTH(checkindate) <= 12")
        ->whereRaw("YEAR(checkindate) >= $tahunawal")
        ->whereRaw("YEAR(checkindate) <= $tahunakhir")
        ->where('parkir_id', '=', Auth::user()->id)
        ->orderBy('checkindate', 'asc')
        ->pluck('bulan');

        $pendapatan = reservasi::select(DB::raw('CAST(SUM(biayatotal) AS INT) biayatotal'))
        ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
        ->whereRaw("MONTH(reservasis.checkindate) >= ".date('m')."")
        ->whereRaw("MONTH(reservasis.checkindate) <= 12")
        ->whereRaw("YEAR(reservasis.checkindate) >= $tahunawal")
        ->whereRaw("YEAR(reservasis.checkindate) <= $tahunakhir")
        ->where('parkir_id', '=', Auth::user()->id)
        ->orderBy('checkindate', 'asc')
        ->groupByRaw('MONTH(checkindate)')
        ->pluck('biayatotal');

        $saldoadmin = 
        reservasi::leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
        ->whereRaw("MONTH(checkindate) >= ".date('m')."")
        ->whereRaw("MONTH(checkindate) <= 12")
        ->whereRaw("YEAR(checkindate) >= $tahunawal")
        ->whereRaw("YEAR(checkindate) <= $tahunakhir")
        ->where('parkir_id', '=', Auth::user()->id)
        ->sum('biayatotal');

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.slot')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->where('parkir_id', '=', Auth::user()->id)
            ->get();

        // dd($saldoadmin);

        return view('pengelola.rekap')->with('rekap', $data)->with('pendapatan', $pendapatan)->with('months', $months)->with('saldoadmin', $saldoadmin)->with('bulanakhir', $bulanakhir)->with('bulanawal', $bulanawal);
    }

    function filterrekap(Request $request)
    {
        $reserve = DB::table('reservasis');
        $bulanawal = $request->bulanawal;
        $bulanawalparsed = date('m', strtotime($bulanawal));
        $tahunawal = date('Y', strtotime($bulanawal));
        
        $bulanakhir = $request->bulanakhir;
        $bulanakhirparsed = date('m', strtotime($bulanakhir));
        $tahunakhir = date('Y', strtotime($bulanakhir));

        $saldoadmin = 
        reservasi::leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
        ->whereRaw("MONTH(checkindate) >= $bulanawalparsed")
        ->whereRaw("MONTH(checkindate) <= $bulanakhirparsed")
        ->whereRaw("YEAR(checkindate) >= $tahunawal")
        ->whereRaw("YEAR(checkindate) <= $tahunakhir")
        ->where('parkir_id', '=', Auth::user()->id)
        ->sum('biayatotal');

        $months = reservasi::select(DB::raw('DISTINCT MONTHNAME(checkindate) bulan'))
        ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
        ->whereRaw("MONTH(checkindate) >= $bulanawalparsed")
        ->whereRaw("MONTH(checkindate) <= $bulanakhirparsed")
        ->whereRaw("YEAR(checkindate) >= $tahunawal")
        ->whereRaw("YEAR(checkindate) <= $tahunakhir")
        ->where('parkir_id', '=', Auth::user()->id)
        ->orderBy('checkindate', 'asc')
        ->pluck('bulan');

        $pendapatan = reservasi::select(DB::raw('CAST(SUM(biayatotal) AS INT) biayatotal'))
        ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
        ->whereRaw("MONTH(reservasis.checkindate) >= $bulanawalparsed")
        ->whereRaw("MONTH(reservasis.checkindate) <= $bulanakhirparsed")
        ->whereRaw("YEAR(reservasis.checkindate) >= $tahunawal")
        ->whereRaw("YEAR(reservasis.checkindate) <= $tahunakhir")
        ->where('parkir_id', '=', Auth::user()->id)
        ->orderBy('checkindate', 'asc')
        ->groupByRaw('MONTH(checkindate)')
        ->pluck('biayatotal');

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.slot')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->where('parkir_id', '=', Auth::user()->id)
            ->get();

        // dd($saldoadmin);

        return view('pengelola.rekap')->with('rekap', $data)->with('months', $months)->with('pendapatan', $pendapatan)->with('saldoadmin', $saldoadmin)->with('bulanakhir', $bulanakhir)->with('bulanawal', $bulanawal);
    }

    function admingetanalytics()
    {
        $reserve = DB::table('reservasis');
        $bulanawal = date('Y-m');
        $bulanakhir = "".date('Y')."-12";
        $tahunawal = date('Y'); 
        $tahunakhir = date('Y');

        $months = reservasi::select(DB::raw('DISTINCT MONTHNAME(checkindate) bulan'))
        ->whereRaw("MONTH(reservasis.checkindate) >= ".date('m')."")
        ->whereRaw("MONTH(reservasis.checkindate) <= 12")
        ->whereRaw("YEAR(reservasis.checkindate) >= $tahunawal")
        ->whereRaw("YEAR(reservasis.checkindate) <= $tahunakhir")
        ->orderBy('checkindate', 'asc')
        ->pluck('bulan');
        // ->get();
        // ->toArray();

        $pendapatan = reservasi::select(DB::raw('CAST(SUM(biayatotal) AS INT) biayatotal'))
        ->whereRaw("MONTH(reservasis.checkindate) >= ".date('m')."")
        ->whereRaw("MONTH(reservasis.checkindate) <= 12")
        ->whereRaw("YEAR(reservasis.checkindate) >= $tahunawal")
        ->whereRaw("YEAR(reservasis.checkindate) <= $tahunakhir")
        ->orderBy('checkindate', 'asc')
        ->groupByRaw('MONTH(checkindate)')
        ->pluck('biayatotal');

        $saldoadmin = 
        reservasi::whereRaw("MONTH(reservasis.checkindate) >= ".date('m')."")
        ->whereRaw("MONTH(reservasis.checkindate) <= 12")
        ->whereRaw("YEAR(reservasis.checkindate) >= $tahunawal")
        ->whereRaw("YEAR(reservasis.checkindate) <= $tahunakhir")
        ->sum('biayatotal');

        $jumlahuser = User::where('role', '=', 'member')
        ->whereRaw("MONTH(users.created_at) >= ".date('m')."")
        ->whereRaw("MONTH(users.created_at) <= 12")
        ->whereRaw("YEAR(users.created_at) >= $tahunawal")
        ->whereRaw("YEAR(users.created_at) <= $tahunakhir")
        ->count();

        $jumlahperusahaan = User::where('role', '=', 'pengelola')
        ->whereRaw("MONTH(users.created_at) >= ".date('m')."")
        ->whereRaw("MONTH(users.created_at) <= 12")
        ->whereRaw("YEAR(users.created_at) >= $tahunawal")
        ->whereRaw("YEAR(users.created_at) <= $tahunakhir")
        ->count();

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.slot', 'users.saldo')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->get();

        // dd($pendapatan);

        return view('admin.analytics')->with('analytics', $data)->with('months', $months)->with('pendapatan', $pendapatan)->with('saldoadmin', $saldoadmin)->with('jumlahuser', $jumlahuser)->with('jumlahperusahaan', $jumlahperusahaan)->with('bulanakhir', $bulanakhir)->with('bulanawal', $bulanawal); 
    }

    function adminfilteranalytics(Request $request)
    {
        $bulanawal = $request->bulanawal;
        $bulanawalparsed = date('m', strtotime($bulanawal));
        $tahunawal = date('Y', strtotime($bulanawal));
        
        $bulanakhir = $request->bulanakhir;
        $bulanakhirparsed = date('m', strtotime($bulanakhir));
        $tahunakhir = date('Y', strtotime($bulanakhir));

        $reserve = DB::table('reservasis');

        $months = reservasi::select(DB::raw('DISTINCT MONTHNAME(checkindate) bulan'))
        ->whereRaw("MONTH(reservasis.checkindate) >= $bulanawalparsed")
        ->whereRaw("MONTH(reservasis.checkindate) <= $bulanakhirparsed")
        ->whereRaw("YEAR(reservasis.checkindate) >= $tahunawal")
        ->whereRaw("YEAR(reservasis.checkindate) <= $tahunakhir")
        ->orderBy('checkindate', 'asc')
        ->pluck('bulan');

        $pendapatan = reservasi::select(DB::raw('CAST(SUM(biayatotal) AS INT) biayatotal'))
        ->whereRaw("MONTH(reservasis.checkindate) >= $bulanawalparsed")
        ->whereRaw("MONTH(reservasis.checkindate) <= $bulanakhirparsed")
        ->whereRaw("YEAR(reservasis.checkindate) >= $tahunawal")
        ->whereRaw("YEAR(reservasis.checkindate) <= $tahunakhir")
        ->orderBy('checkindate', 'asc')
        ->groupByRaw('MONTH(checkindate)')
        ->pluck('biayatotal');

        $saldoadmin = 
        reservasi::whereRaw("MONTH(reservasis.checkindate) >= $bulanawalparsed")
        ->whereRaw("MONTH(reservasis.checkindate) <= $bulanakhirparsed")
        ->whereRaw("YEAR(reservasis.checkindate) >= $tahunawal")
        ->whereRaw("YEAR(reservasis.checkindate) <= $tahunakhir")
        ->sum('biayatotal');

        $jumlahuser = User::where('role', '=', 'member')
        ->whereRaw("MONTH(users.created_at) >= $bulanawalparsed")
        ->whereRaw("MONTH(users.created_at) <= $bulanakhirparsed")
        ->whereRaw("YEAR(users.created_at) >= $tahunawal")
        ->whereRaw("YEAR(users.created_at) <= $tahunakhir")
        ->count();

        $jumlahperusahaan = User::where('role', '=', 'pengelola')
        ->whereRaw("MONTH(users.created_at) >= $bulanawalparsed")
        ->whereRaw("MONTH(users.created_at) <= $bulanakhirparsed")
        ->whereRaw("YEAR(users.created_at) >= $tahunawal")
        ->whereRaw("YEAR(users.created_at) <= $tahunakhir")
        ->count();

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.slot', 'users.saldo')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->get();

        // dd($bulanawalparsed);

        return view('admin.analytics')->with('analytics', $data)->with('months', $months)->with('pendapatan', $pendapatan)->with('saldoadmin', $saldoadmin)->with('jumlahuser', $jumlahuser)->with('jumlahperusahaan', $jumlahperusahaan)->with('bulanakhir', $bulanakhir)->with('bulanawal', $bulanawal); 
    }

    function admingettransaksi()
    {

        $reserve = DB::table('reservasis');

        $user = User::find(Auth::user()->id);
        $parkir = reservasi::where('parkir_id', '=', Auth::user()->id)->get();


        // $parkir = reservasi::all()->where('parkir_id', Auth::user()->id);

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.slot')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->get();

        //  dd($data);

        return view('admin.transaksi', ['transaksi' => $data]);
    }

    function admingetsearchtransaksi(Request $request)
    {
        $search  = $request->search == null ? '' : $request->search;
        $reserve = DB::table('reservasis');

        $user = User::find(Auth::user()->id);
        $parkir = reservasi::where('parkir_id', '=', Auth::user()->id)->get();


        // $parkir = reservasi::all()->where('parkir_id', Auth::user()->id);

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.slot')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->orWhere('reg_parkirs.name', 'like', '%' . $search . '%')
            ->orWhere('users.name', 'like', '%' . $search . '%')
            ->orWhere('biayatotal', 'like', '%' . $search . '%')
            ->get();

        //  dd($data);

        return view('admin.transaksi', ['transaksi' => $data]);
    }

    function admingetuser()
    {
        $data = User::all();

        return view('admin.user')->with('data', $data);
    }

    function admingetprofile()
    {
        $user = User::find(Auth::user()->id);

        return view('admin.profile')->with('user', $user);
    }

    function admingetpengelola()
    {
        $data = User::all();

        return view('admin.pengelola')->with('data', $data);
    }

    function adminsearchpengelola(Request $request)
    {
        $search  = $request->search == null ? '' : $request->search;
        $data = User::where('name', 'like', '%' . $search . '%')->orWhere('id', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')->get();

        return view('admin.searchpengelola')->with('data', $data)->with('search', $search);
    }

    function adminsearchuser(Request $request)
    {
        $search  = $request->search == null ? '' : $request->search;
        $data = User::where('name', 'like', '%' . $search . '%')->orWhere('id', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')->get();

        return view('admin.searchuser')->with('data', $data)->with('search', $search);
    }

    function usergetreservasi()
    {
        $reserve = DB::table('reservasis');

        $user = User::find(Auth::user()->id);
        $parkir = reservasi::where('user_id', '=', Auth::user()->id)->get();
        // $parkir = reservasi::all()->where('parkir_id', Auth::user()->id);

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.name', 'reg_parkirs.image', 'reg_parkirs.lokasi','reg_parkirs.slot', 'reg_parkirs.biaya', 'users.saldo', 'users.name')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reservasis.user_id')
            ->where('reservasis.user_id', '=', Auth::user()->id)
            ->get();

        //dd($data);

        return view('user.search', ['reservasis' => $data]);
    }

    function usergethistory()
    {
        $reserve = DB::table('reservasis');

        $user = User::find(Auth::user()->id);
        $parkir = reservasi::where('user_id', '=', Auth::user()->id)->get();


        // $parkir = reservasi::all()->where('parkir_id', Auth::user()->id);

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.name', 'reg_parkirs.image', 'reg_parkirs.lokasi')
            ->join('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->where('reservasis.user_id', '=', Auth::user()->id)
            ->get();

        //  dd($data);

        return view('user.history', ['reservasis' => $data]);
    }

    function admingetriwayat()
    {
        $reserve = DB::table('reservasis');

        $user = User::find(Auth::user()->id);
        $parkir = reservasi::where('user_id', '=', Auth::user()->id)->get();


        // $parkir = reservasi::all()->where('parkir_id', Auth::user()->id);

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.name', 'reg_parkirs.slot', 'reg_parkirs.lokasi')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->get();

        //   dd($data);

        return view('admin.riwayat', ['riwayat' => $data]);
    }

    function adminsearchriwayat(Request $request)
    {
        $search  = $request->search == null ? '' : $request->search;
        $reserve = DB::table('reservasis');

        $user = User::find(Auth::user()->id);
        $parkir = reservasi::where('user_id', '=', Auth::user()->id)->get();


        // $parkir = reservasi::all()->where('parkir_id', Auth::user()->id);

        $data = $reserve
            ->select('reservasis.*', 'reg_parkirs.name', 'reg_parkirs.slot', 'reg_parkirs.lokasi')
            ->leftJoin('reg_parkirs', 'reg_parkirs.id', 'reservasis.parkir_id')
            ->leftJoin('users', 'users.id', 'reg_parkirs.user_id')
            ->orWhere('reg_parkirs.name', 'like', '%' . $search . '%')
            ->orWhere('users.name', 'like', '%' . $search . '%')
            ->orWhere('biayatotal', 'like', '%' . $search . '%')
            ->get();

        //   dd($data);

        return view('admin.searchriwayat', ['riwayat' => $data]);
    }
}
