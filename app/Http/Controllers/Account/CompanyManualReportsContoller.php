<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\client\Order;
use App\Models\Counter;
use App\Models\Currency;
use App\Models\OfflineReports;
use App\Models\OfflineReportsDates;
use App\Models\Order_status;
use Carbon\Carbon;
use DateTime;
use Session;
class CompanyManualReportsContoller extends Controller
{
    public function index()
    {
        if (Helper::getFeatureUses('offline-reports') == false) {
            return abort(404);
        }
        return view('account.company.dashboard.dashboard');
    }

    public function manualreportsview()
    {
        $visitors = Counter::count();
        $directory_id = Auth::user()->directory_id;
        $deliveredOrders = Order::with('orderstatus')->where('directory_id', $directory_id)->where('status', 4)->count();
        return view('account.company.dashboard.manualreports', compact('deliveredOrders', 'visitors'));
    }

    public function allvisitors(Request $request)
    {
        if (Helper::getFeatureUses('offline-reports') == false) {
            return abort(404);
        }
        $directory_id = Auth::user()->directory_id;
        if ($request->type == 'filter') {
            session(['filterdata' => $request->all()]);
            $start_date1 = $request->fromdate;
            if (!empty($start_date1)) {
                $start_date = Carbon::parse($request->fromdate)->toDateString();
                $todate = Carbon::parse($request->todate)->toDateString();
            } else {
                $start_date = '';
                $todate = '';
            }
            if ($request->module == 'visitors') {
                $OfflineReportsData = OfflineReports::leftjoin('offline_reports_dates', 'offline_reports_dates.offline_reports_id', '=', 'offline_reports.id')
                    ->WhereBetween('offline_reports_dates.date', [$start_date, $todate])
                    ->where('offline_reports.type', 'visitors')
                    ->where('offline_reports.directory_id', $directory_id)
                    ->groupBy('offline_reports_dates.offline_reports_id')
                    ->select(['offline_reports.*', 'offline_reports_dates.date', 'offline_reports_dates.offline_reports_id'])->paginate(20);
            }
            return view('account.company.offlinereports.index', ['reportsdata' => $OfflineReportsData]);
        } else {
            session()->forget(['filterdata']);
            $reportsdata = OfflineReports::with('user')->where('directory_id', $directory_id)->where('type', 'visitors')->orderBy('id', 'DESC')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('account.company.offlinereports.index', ['reportsdata' => $reportsdata, 'data' => $data]);
        }
    }

    public function allreceiptclosing(Request $request)
    {
        if (Helper::getFeatureUses('offline-reports') == false) {
            return abort(404);
        }
        $directory_id = Auth::user()->directory_id;
        if ($request->type == 'filter') {
            session(['filterdata' => $request->all()]);
            $start_date1 = $request->fromdate;
            if (!empty($start_date1)) {
                $start_date = Carbon::parse($request->fromdate)->toDateString();
                $todate = Carbon::parse($request->todate)->toDateString();
            } else {
                $start_date = '';
                $todate = '';
            }
            if ($request->module == 'receiptclosing') {
                $OfflineReportsData = OfflineReports::leftjoin('offline_reports_dates', 'offline_reports_dates.offline_reports_id', '=', 'offline_reports.id')
                    ->WhereBetween('offline_reports_dates.date', [$start_date, $todate])
                    ->where('offline_reports.type', 'receipt_closing')
                    ->where('offline_reports.directory_id', $directory_id)
                    ->groupBy('offline_reports_dates.offline_reports_id')
                    ->select(['offline_reports.*', 'offline_reports_dates.date', 'offline_reports_dates.offline_reports_id'])->paginate(20);
            }
            return view('account.company.offlinereports.index', ['reportsdata' => $OfflineReportsData]);
        } else {
            session()->forget(['filterdata']);
            $reportsdata = OfflineReports::with('user')->where('directory_id', $directory_id)->where('type', 'receipt_closing')->orderBy('id', 'DESC')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('account.company.offlinereports.index', ['reportsdata' => $reportsdata, 'data' => $data]);
        }
    }

    public function allextrareports(Request $request)
    {
        if (Helper::getFeatureUses('offline-reports') == false) {
            return abort(404);
        }
        $directory_id = Auth::user()->directory_id;
        if ($request->type == 'filter') {
            session(['filterdata' => $request->all()]);
            $start_date1 = $request->fromdate;
            if (!empty($start_date1)) {
                $start_date = Carbon::parse($request->fromdate)->toDateString();
                $todate = Carbon::parse($request->todate)->toDateString();
            } else {
                $start_date = '';
                $todate = '';
            }
            if ($request->module == 'extra') {
                $OfflineReportsData = OfflineReports::leftjoin('offline_reports_dates', 'offline_reports_dates.offline_reports_id', '=', 'offline_reports.id')
                    ->WhereBetween('offline_reports_dates.date', [$start_date, $todate])
                    ->where('offline_reports.type', 'extra')
                    ->where('offline_reports.directory_id', $directory_id)
                    ->groupBy('offline_reports_dates.offline_reports_id')
                    ->select(['offline_reports.*', 'offline_reports_dates.date', 'offline_reports_dates.offline_reports_id'])->paginate(20);
            }
            return view('account.company.offlinereports.index', ['reportsdata' => $OfflineReportsData]);
        } else {
            session()->forget(['filterdata']);
            $reportsdata = OfflineReports::with('user')->where('directory_id', $directory_id)->where('type', 'extra')->orderBy('id', 'DESC')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('account.company.offlinereports.index', ['reportsdata' => $reportsdata, 'data' => $data]);
        }
    }

    public function create(Request $request)
    {
        unset($request['_token']);
        $validator = \Validator::make($request->all(), [
            'city' => 'required',
            'zipcode' => 'required|numeric',
            'fromdate' => 'required',
            'todate' => 'required',
            'value' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        } else {
            $data = new OfflineReports();
            $data->fromdate = $request->input('fromdate');
            $data->todate = $request->input('todate');
            $data->value = $request->input('value');
            $data->type = $request->input('type');
            $data->city = $request->input('city');
            $data->zipcode = $request->input('zipcode');
            $data->directory_id = Auth::user()->directory_id;
            $res = $data->save();

            $insertedid = $data->id;
            $start = $request->input('fromdate');
            $end = $request->input('todate');
            $start  = new DateTime($start);
            $end    = new DateTime($end);
            $format = 'Y-m-d';
            $invert = $start > $end;

            $dates = array();
            $dates[] = $start->format($format);
            while ($start != $end) {
                $start->modify(($invert ? '-' : '+') . '1 day');
                $dates[] = $start->format($format);
            }
            foreach ($dates as $val) {
                $dataDates = new OfflineReportsDates();
                $dataDates->offline_reports_id = $insertedid;
                $dataDates->date = $val;
                $res = $dataDates->save();
            }
            if ($res) {
                return response()->json(['status' => true, 'message' => 'Created successfull.']);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
            }
        }
    }

    public function edit($id = 0)
    {
        $rData = OfflineReports::findOrFail($id);
        return view('account.company.offlinereports.edit', compact('rData'));
    }

    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'city' => 'required',
            'zipcode' => 'required',
            'fromdate'  => 'required',
            'todate' => 'required',
            'value' => 'required'
        ]);
        OfflineReports::whereId($id)->update($updateData);
        return redirect()->back()->with('msg', 'Report has been updated');
    }

    public function changeStatus(Request $request)
    {
        $review = OfflineReports::find($request->id);
        $review->status = $request->status;
        $review->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        OfflineReports::find($id)->delete();
        return redirect()->back()->with('msg', 'Review deleted successfully');
    }
}
