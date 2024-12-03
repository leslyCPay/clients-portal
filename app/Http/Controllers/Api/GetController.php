<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getInsuredData(Request $request){

        $client = HttpClient::create();

        $response = $client->request('GET', $this->baseUrl."/getnamebyemail?e_mail=".$request->email);

        if ($response->getStatusCode() === 404) {
            return new Response('Record does not exist', 404);
        }
        $content = $response->getContent();

        //var_dump($content);

        $res = json_decode($content, true);

        $names='';

        foreach ($res['data'] as $value) {

            $names = $names.$value['insured_name'].',';

        }
        $last= rtrim($names, ',');

        return response()->json($last);

    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCases(Request $request)
    {

        $client = HttpClient::create();

        $response = $client->request('GET', $this->baseUrl."/getcasesbyinsured?names=".$request->names);

        if ($response->getStatusCode() === 404) {
            return new Response('Record does not exist', 404);
        }
        $content = $response->getContent();

        $res = json_decode($content, true);

        $res = $res['data'];

        $includeFields = ['case_id', 'claim_number', 'status']; // Campos que SÍ queremos devolver

        $filteredData = array_map(function ($case) use ($includeFields) {
            return array_filter($case, function ($key) use ($includeFields) {
                return in_array($key, $includeFields);
            }, ARRAY_FILTER_USE_KEY);
        }, $res);

        return response()->json($filteredData);

    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getDetailsOfCase(Request $request){

        $client = HttpClient::create();

        $response = $client->request('GET', $this->baseUrl."/getalldetails?case_id=".$request->case_id);

        if ($response->getStatusCode() === 404) {
            return new Response('.Record does not exist', 404);
        }
        $content = $response->getContent();

        $res = json_decode($content, true);

        return response()->json($res['data']);
    }





}
