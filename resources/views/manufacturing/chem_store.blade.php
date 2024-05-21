@extends('layouts.app-v2')
@section('content-v2')
<div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Chemstore</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Chemstore Report</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-7"><b>MATERIALS WEIGHING SHEET - HCL/PROD/001</b></div>
                    <div class="col-md-5"><b>BATCH:</b>H190017</div>
                </div>
            </div>
            <div class="card-body">
                <table class="table-light table-bordered  border-secondary batch-data">
                    <thead>
                    <tr class="chemical-heading border-secondary text-center">
                        <th scope="col" class="border-secondary">No.</th>
                        <th scope="col" class="border-secondary">MATERIAL <br> CODE</th>
                        <th scope="col" class="border-secondary">STANDARD WT  <br>(G)</th>
                        <th scope="col" class="border-secondary">WEIGHED QTY  <br>(G)</th>
                        <th scope="col" class="border-secondary">VARIANCE</th>
                        <th scope="col" class="border-secondary">BATCH NUMBER</th>
                        <th scope="col" class="border-secondary">EXPIRY DATE</th>
                        <th scope="col" class="border-secondary">SUPPLIER CODE</th>
                        <th scope="col" class="border-secondary">RAW MATERIALS QNTY/QLTY  <br>REMARKS</th>
                        <th scope="col" class="border-secondary">WEIGHED BY</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">1</th>
                        <td class="border-secondary">SOLV-OAO</td>
                        <td class="border-secondary">17,920.0</td>
                        <td class="border-secondary"><b>20,000.0</b></td>
                        <td class="border-secondary var"><b>(2,080.0)</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">2</th>
                        <td class="border-secondary">MECA-000</td>
                        <td class="border-secondary">640.0</td>
                        <td class="border-secondary"><b>32.0</b></td>
                        <td class="border-secondary var"><b>608.0)</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">3</th>
                        <td class="border-secondary">ACDA-040</td>
                        <td class="border-secondary">1,280.0</td>
                        <td class="border-secondary"><b>2,240.0</b></td>
                        <td class="border-secondary var"><b>(960.0)</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">4</th>
                        <td class="border-secondary">TRET-000</td>
                        <td class="border-secondary">2,560.0</td>
                        <td class="border-secondary"><b>3,340.8</b></td>
                        <td class="border-secondary var"><b>(780.8)</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">5</th>
                        <td class="border-secondary">CERO-0G0</td>
                        <td class="border-secondary">1,280.0</td>
                        <td class="border-secondary"><b>20,000.0</b></td>
                        <td class="border-secondary var"><b>1,280.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">6</th>
                        <td class="border-secondary">VACT-031</td>
                        <td class="border-secondary">2,560.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>2,560.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">7</th>
                        <td class="border-secondary">SAAG-030</td>
                        <td class="border-secondary">640.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>640.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">8</th>
                        <td class="border-secondary">SAAG-000</td>
                        <td class="border-secondary">1,280.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>1,280.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">9</th>
                        <td class="border-secondary">SAAG-020</td>
                        <td class="border-secondary">640.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>640.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center metro">
                        <th scope="row" class="border-secondary"></th>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">11</th>
                        <td class="border-secondary">SAAG-050</td>
                        <td class="border-secondary">1,920.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>1,920.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">12</th>
                        <td class="border-secondary">SAAG-040</td>
                        <td class="border-secondary">2,560.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>2,560.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">13</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">14</th>
                        <td class="border-secondary">OGOL-040</td>
                        <td class="border-secondary">7,680.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>7,680.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">15</th>
                        <td class="border-secondary">COTA-030</td>
                        <td class="border-secondary">1,280.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>1,280</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">16</th>
                        <td class="border-secondary">VACT-020</td>
                        <td class="border-secondary">5,760.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>5,760.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">17</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">18</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">19</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">20</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center metro">
                        <th scope="row" class="border-secondary"></th>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">22</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center metro">
                        <th scope="row" class="border-secondary"></th>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">24</th>
                        <td class="border-secondary">SAAG-060</td>
                        <td class="border-secondary">2,560.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>2,560.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">25</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                </table>
                <div class="row end-details">
                    <div class="col-md-3">Prepared By...................................</div>
                    <div class="col-md-3">Date.........................</div>
                    <div class="col-md-3">Received By....................................</div>
                    <div class="col-md-3">Date..............................</div>
                </div>

                <div class="row mt-5">
                    <div class="col-md-7"><b>MATERIALS WEIGHING SHEET - HCL/PROD/001</b></div>
                    <div class="col-md-5"><b>BATCH:</b>H190017</div>
                </div>
                <table class="table-light table-bordered  border-secondary batch-data p-3">
                    <thead>
                    <tr class="chemical-heading border-secondary text-center">
                        <th scope="col" class="border-secondary">No.</th>
                        <th scope="col" class="border-secondary">MATERIAL <br> CODE</th>
                        <th scope="col" class="border-secondary">STANDARD WT<br>(G)</th>
                        <th scope="col" class="border-secondary">WEIGHED QTY<br>(G)</th>
                        <th scope="col" class="border-secondary">VARIANCE</th>
                        <th scope="col" class="border-secondary">BATCH NUMBER</th>
                        <th scope="col" class="border-secondary">EXPIRY DATE</th>
                        <th scope="col" class="border-secondary">SUPPLIER CODE</th>
                        <th scope="col" class="border-secondary">RAW MATERIALS QNTY/QLTY<br>REMARKS</th>
                        <th scope="col" class="border-secondary">WEIGHED BY</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">1</th>
                        <td class="border-secondary">ANTM-000</td>
                        <td class="border-secondary">3,840.00</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>3,840.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">2</th>
                        <td class="border-secondary">OGFR-020</td>
                        <td class="border-secondary">5,760.00</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>5,760.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">3</th>
                        <td class="border-secondary">OGFR-040</td>
                        <td class="border-secondary">2,560.00</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>2,560.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">4</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">5</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">6</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">7</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">8</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">9</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center metro">
                        <th scope="row" class="border-secondary"></th>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">11</th>
                        <td class="border-secondary">PHPL-00</td>
                        <td class="border-secondary">3,840.0</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>3,840.0</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">12</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">13</th>
                        <td class="border-secondary">0</td>
                        <td class="border-secondary">-</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b>-</b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>

                </table>

                <div class="row mt-5">
                    <div class="col-md-7"><b>PACKAGING RECORD SHEET - HCL/PROD/001</b></div>
                    <div class="col-md-5"><b>BATCH:</b>H190017</div>
                </div>
                <table class="table-light table-bordered  border-secondary batch-data">
                    <thead>
                    <tr class="chemical-heading border-secondary text-center">
                        <th scope="col" class="border-secondary">No.</th>
                        <th scope="col" class="border-secondary">MATERIAL <br> CODE</th>
                        <th scope="col" class="border-secondary">STANDARD WT<br>(G)</th>
                        <th scope="col" class="border-secondary">WEIGHED QTY<br>(G)</th>
                        <th scope="col" class="border-secondary">VARIANCE</th>
                        <th scope="col" class="border-secondary">BATCH NUMBER</th>
                        <th scope="col" class="border-secondary">EXPIRY DATE</th>
                        <th scope="col" class="border-secondary">SUPPLIER CODE</th>
                        <th scope="col" class="border-secondary">RAW MATERIALS QNTY/QLTY<br>REMARKS</th>
                        <th scope="col" class="border-secondary">WEIGHED BY</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">1</th>
                        <td class="border-secondary">Bottles 150g</td>
                        <td class="border-secondary">432</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var "><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">2</th>
                        <td class="border-secondary">PUMP LID</td>
                        <td class="border-secondary">432</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">3</th>
                        <td class="border-secondary">SHRINK WRAP</td>
                        <td class="border-secondary">18</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">4</th>
                        <td class="border-secondary">CRTON & STK</td>
                        <td class="border-secondary">9</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>
                    <tr class="border-secondary text-center ">
                        <th scope="row" class="border-secondary">5</th>
                        <td class="border-secondary">OTHER PCKGNG</td>
                        <td class="border-secondary">SPECIFY</td>
                        <td class="border-secondary"><b></b></td>
                        <td class="border-secondary var"><b></b></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                        <td class="border-secondary"></td>
                    </tr>

                </table>

                <div class="row end-details">
                    <div class="col-md-3">Prepared By...................................</div>
                    <div class="col-md-3">Date.........................</div>
                    <div class="col-md-3">Received By....................................</div>
                    <div class="col-md-3">Date..............................</div>
                </div>
            </div>
        </div>
</div>
<style>
    .table-light {
        width: 100%; /* Makes the table width 100% of its container */
    }

    .table-light th, .table-light td {
        padding: 8px; /* Increases padding to make cells larger */
    }
    .chemical-heading{
        background-color: #0001CD;
        color: #fff;
    }
    .metro{
        background-color: #BFBEBE;
        color: #fff;
    }

    .end-details{
        margin-top: 20px;
    }
    .table-light {
        width: 100%;
    }

    .batch-data{
        font-size:11px;
    }
    textarea {
        width: 100%;
        padding: 4px;
        margin: 0;
        border: none;
        background: inherit;  /* Inherits the background from the parent <tr> */
        color: inherit;  /* Optional: Inherits text color from the parent <tr> */
        resize: none;  /* Disables resizing of the textarea */
        overflow: auto;  /* Adds a scrollbar if the text overflows */
        font-style:italic;
    }
    .comment{
        font-size:10px;
        font-style:italic;
    }
    .var{
        color:#CB2829;
    }
</style>


@endsection
