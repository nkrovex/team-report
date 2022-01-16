<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeamReportCollection;
use App\Models\Team;
use App\Services\Formatter\Formatter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeamReportController extends Controller
{
    public function getReportJSON(): StreamedResponse
    {
        $formattedData = Formatter::make($this->getReportData(), Formatter::ARR)->toJson();

        return $this->sendReport($formattedData, Formatter::JSON);
    }

    public function getReportXML(): StreamedResponse
    {
        $formattedData = Formatter::make($this->getReportData(), Formatter::ARR)->toXml();

        return $this->sendReport($formattedData, Formatter::XML);
    }

    private function getReportData(): array
    {
        $teams = Team::with('accounts')->get();

        return (new TeamReportCollection($teams))->toResponse(app('request'))->getData(true);
    }

    private function sendReport(string $data, string $format): StreamedResponse
    {
        return response()->streamDownload(function () use ($data) {
            echo $data;
        }, 'report.' . $format);
    }
}
