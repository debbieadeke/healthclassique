@extends('layouts.app-v2')
@section('content-v2')
    <div class="container">
        <div class="card text-bg-light">
            <div class="card-body">
                <h1>Production</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">Production Report</li>
                    </ol>
                </nav>
            </div>
        </div>
    <div class="card">
    <div class="card-header">
        <div class="row ">
            <div class="col-md-7"><b>PRODUCTION FLOOR RECORD SHEET - HCL/PROD/001</b></div>
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
            <th scope="col" class="border-secondary">CONFIRMED WT<br>(G)</th>
            <th scope="col" class="border-secondary">VARIANCE</th>
            <th scope="col" class="border-secondary">BATCH  NUMBER</th>
            <th scope="col" class="border-secondary">SUPPLIER CODE</th>
            <th scope="col" class="border-secondary">PROCESS  <br>VSL/PT/BK</th>
            <th scope="col" class="border-secondary">PT& BK <br>CLASS</th>
            <th scope="col" class="border-secondary">RAW MATERIALS QNTY/QLTY  <br>REMARKS</th>
            <th scope="col" class="border-secondary">CONFIRMED  <br>BY</th>

        </tr>
        </thead>
        <tbody>
        <tr class="border-secondary text-center ">
            <th scope="row" class="border-secondary">1</th>
            <td class="border-secondary">SOLV-OAO</td>
            <td class="border-secondary">17,920.0</td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>(2,080.0)</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>608.0)</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>(960.0)</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>2,560.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>1,280.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>2,560.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>640.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>1,280.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>640.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"></td>
            <td class="border-secondary"></td>

        </tr>
        <tr class="border-secondary text-center ">
            <th scope="row" class="border-secondary">11</th>
            <td class="border-secondary">SAAG-050</td>
            <td class="border-secondary">1,920.0</td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>1,920.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>2,560.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>7,680.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>1,280</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>5,760.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b></b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b></b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>2,560.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
        <div class="col-md-5"><b>BATCH:</b>H50046</div>
    </div>
    <table class="table-light table-bordered  border-secondary batch-data">
        <thead>
        <tr class="chemical-heading border-secondary text-center">
            <th scope="col" class="border-secondary">No.</th>
            <th scope="col" class="border-secondary">MATERIAL <br> CODE</th>
            <th scope="col" class="border-secondary">STANDARD WT  <br>(G)</th>
            <th scope="col" class="border-secondary">WEIGHED QTY  <br>(G)</th>
            <th scope="col" class="border-secondary">CONFIRMED WT<br>(G)</th>
            <th scope="col" class="border-secondary">VARIANCE</th>
            <th scope="col" class="border-secondary">BATCH  NUMBER</th>
            <th scope="col" class="border-secondary">SUPPLIER CODE</th>
            <th scope="col" class="border-secondary">PROCESS  <br>VSL/PT/BK</th>
            <th scope="col" class="border-secondary">PT& BK <br>CLASS</th>
            <th scope="col" class="border-secondary">RAW MATERIALS QNTY/QLTY  <br>REMARKS</th>
            <th scope="col" class="border-secondary">CONFIRMED  <br>BY</th>

        </tr>
        </thead>
        <tbody>
        <tr class="border-secondary text-center ">
            <th scope="row" class="border-secondary">1</th>
            <td class="border-secondary">ANTM-000</td>
            <td class="border-secondary">3,840.00</td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>3,840.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>5,760.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>2,560.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b></b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>3,840.0</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
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
            <td class="border-secondary"><b></b></td>
            <td class="border-secondary var"><b>-</b></td>
            <td class="border-secondary"></td>
            <td class="border-secondary"></td>
            <td class="border-secondary"></td>
            <td class="border-secondary"></td>
            <td class="border-secondary"></td>
            <td class="border-secondary"></td>
        </tr>
    </table>

    <table class="table-light table-bordered border-black batch-data2">
        <thead>
        <tr class="chemical-heading border-black text-center">
            <th scope="col" class="border-dark" colspan="6">OTHER MANUFACTURING RECORDS</th>
            <th scope="col" class="border-dark" colspan="5">GENERAL NOTES, COMMENTS & OBSERVATIONS</th>
        </tr>
        </thead>
        <tbody>
        <tr class="border-dark text-center">
            <th scope="row" class="border-dark" rowspan="2">Mixing Temperatures</th>
            <td class="border-dark" colspan="1"><b>Phase A</b></td>
            <td class="border-dark" colspan="1"><b>Phase B</b></td>
            <td class="border-dark" colspan="1"><b>Start Mix Temp</b></td>
            <td class="border-dark" colspan="1"><b>Phase C</b></td>
            <td class="border-dark" colspan="1"><b>Phase D</b></td>
            <td class="border-dark comment" rowspan="2"></td>
        </tr>
        <tr>
            <td class="border-dark text-center phases"><b>23.8°C</b></td>
            <td class="border-dark text-center phases"><b>23.8°C</b></td>
            <td class="border-dark text-center phases"><b>23.8°C</b></td>
            <td class="border-dark text-center phases"><b>23.8°C</b></td>
            <td class="border-dark text-center phases"><b>23.8°C</b></td>
        </tr>
        <tr class="border-dark text-center">
            <th scope="row" class="border-dark" rowspan="2">Homoge Parameters</th>
            <td class="border-dark" colspan="1"><b>Start Speed/T</b></td>
            <td class="border-dark" colspan="2"><b>Subs.Speed/T/Intvis</b></td>
            <td class="border-dark" colspan="1"><b>Stirring Start</b></td>
            <td class="border-dark" colspan="1"><b>Final Stirring</b></td>
            <td class="border-dark comment" rowspan="2"></td>
        </tr>
        <tr>
            <td class="border-dark text-center phases"><b>-</b></td>
            <td class="border-dark text-center phases" colspan="2"><b>-</b></td>
            <td class="border-dark text-center phases"><b>15 rpm</b></td>
            <td class="border-dark text-center phases"><b>25 RPM</b></td>
        </tr>
        <tr class="border-dark text-center">
            <th scope="row" class="border-dark" rowspan="2">Cooling Parameters</th>
            <td class="border-dark" colspan="1"><b>H20 Flow RT</b></td>
            <td class="border-dark" colspan="1"><b>Start T/Temp</b></td>
            <td class="border-dark" colspan="1"><b>End T/Temp</b></td>
            <td class="border-dark" colspan="1"><b>Cooling Time</b></td>
            <td class="border-dark" colspan="1"><b></b></td>
            <td class="border-dark comment" rowspan="2"></td>
        </tr>
        <tr>
            <td class="border-dark text-center phases"><b>-</b></td>
            <td class="border-dark text-center phases"><b>0.00</b></td>
            <td class="border-dark text-center phases"><b>0</b></td>
            <td class="border-dark text-center phases"><b>0</b></td>
            <td class="border-dark text-center phases"></td>
        </tr>
        </tbody>
    </table>
    <div class="row mt-5 mb-3">
        <div class="col-md-2 ">
            <div>
                <span class="process"><b>Process Start Time:</b></span>
            </div>
        </div>
        <div class="col-md-2">
            <input type="time" class="form-control black-border time">
        </div>

        <div class="col-md-2">
            <div>
                <span class="process"><b>Process End Time:</b></span>
            </div>
        </div>
        <div class="col-md-2">
            <input type="time" class="form-control black-border time">
        </div>
        <div class="col-md-2">
            <div>
                <span class="process-at"><b>Final Sample Submitted at:</b></span>
            </div>
        </div>
        <div class="col-md-2">
            <input type="time" class="form-control black-border final">
        </div>



    </div>
    <div class="row mt-5 mb-3 ">
        <div class="col-md-3 process"><b>General Comment:</b></div>
        <div class="col-md-9">
            <input type="text" class="form-control wide-input black-border ant">
        </div>
    </div>
    <div class="row mt-5 mb-3 signature">
        <div class="col-md-2"><b>Process Operator:.......................</b></div>
        <div class="col-md-2"><b>Sign:.....................................</b></div>
        <div class="col-md-2"><b>Date:..................................</b></div>
        <div class="col-md-2"><b>Shift Supervisor:.....................</b></div>
        <div class="col-md-2"><b>Sign:.....................................</b></div>
        <div class="col-md-2"><b>Date:..................................</b></div>
    </div>

    <div class="row mt-5">
        <div class="col-md-7"><b>QUALITY LABORATORY ANALYSIS REPORT</b></div>
        <div class="col-md-5"><b>BATCH:</b></div>
    </div>
    <table class="table-light table-bordered  border-secondary batch-data3">
        <thead>
        <tr class="chemical-heading border-secondary text-center">
            <th scope="col" class="border-secondary">No.</th>
            <th scope="col" class="border-secondary">APPEARANCE</th>
            <th scope="col" class="border-secondary">COLOUR</th>
            <th scope="col" class="border-secondary">ODOR</th>
            <th scope="col" class="border-secondary">VISCOCITY</th>
            <th scope="col" class="border-secondary">pH</th>
            <th scope="col" class="border-secondary">SPECIFIC GRAVITY</th>
            <th scope="col" class="border-secondary">TOTAL VIABLE COUNT</th>
            <th scope="col" class="border-secondary">OTHER</th>
        </tr>
        </thead>
        <tr class="border-secondary text-center ">
            <th scope="row" class="border-secondary">1</th>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
        </tr>
        <tr class="border-secondary text-center ">
            <th scope="row" class="border-secondary">2</th>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
        </tr>

        <thead>
        <tr class="chemical-heading border-secondary text-center">
            <th scope="col" class="border-secondary">No.</th>
            <th scope="col" class="border-secondary">TOTAL ACTIVE MATTER</th>
            <th scope="col" class="border-secondary">RANCIDITY</th>
            <th scope="col" class="border-secondary" colspan="2">SOLUBLE MATTER</th> <!-- Updated this line -->
            <th scope="col" class="border-secondary"></th>
            <th scope="col" class="border-secondary">LATHER VOLUME</th>
            <th scope="col" class="border-secondary">THERMAL STABILITY</th>
            <th scope="col" class="border-secondary">OTHER</th>
        </tr>
        </thead>
        <tr class="border-secondary text-center ">
            <th scope="row" class="border-secondary">1</th>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment" colspan="2"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
        </tr>
        <tr class="border-secondary text-center ">
            <th scope="row" class="border-secondary">2</th>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment" colspan="2"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
            <td class="border-secondary comment"></td>
        </tr>
    </table>
    <div class="row mt-5 mb-3">
        <div class="col-md-3">
            <div class=" text-end">
                <span class="process3"><b>Sample Analysis cleared At:</b></span>
            </div>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control black-border ">
        </div>
        <div class="col-md-3">
            <div class=" text-end">
                <span class="process3"><b>Batch released At:</b></span>
            </div>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control black-border ">
        </div>
    </div>
    <div class="row mt-5 mb-3">
        <div class="col-md-3 process2"><b>Qc Technician Comment:</b></div>
        <div class="col-md-9">
            <input type="text" class="form-control wide-input black-border ant2">
        </div>
    </div>
    <div class="row mt-5 mb-3 process">
        <div class="col-md-5"><b>QC Technician Name:........................................</b></div>
        <div class="col-md-3"><b>Sign:.....................................</b></div>
        <div class="col-md-4"><b>Date:.....................................</b></div>
    </div>
    <div class="row mt-5 mb-3 process">
        <div class="col-md-5"><b>Approved By:........................................</b></div>
        <div class="col-md-3"><b>Sign:.....................................</b></div>
        <div class="col-md-4"><b>Date:.....................................</b></div>
    </div>
    <div class="row">
        <div class="col-md-12 process4"><b>Quality Assurance Manager</b></div>
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
                background-color: #FEC100;
                color: #fff;
            }
            .form-control{
                width:151px;

            }
            .black-border{
                border-color:#000000;
            }
            .time{
                margin-left:-60px;
            }
            .ant{
                margin-left:-114px;
            }
            .ant2{
                margin-left:-100px;
            }
            .final{
                margin-left:-25px;
            }
            .wide-input {
                width: 90%;

            }
            .phases{
                color:#0101CC;
            }
            .signature{
                font-size:11px;
            }
            .process{
                font-size:11px;
                margin-left:-11px;
            }
            .process-at{
                font-size:11px;
                margin-left:-18px;
            }
            .process2{
                font-size:10px;
            }
            .process3 {
                font-size:11px;
                margin-left: -11px;
            }
            .process4{
                font-size:10px;
                margin-left: 44px;
            }


                .metro{
                background-color: #FFD965;
                color: #fff;
            }
            .end-details{
                margin-top: 20px;
            }
            .batch-data{
                font-size:10px;
            }
            .batch-data2{
                font-size:12px;
            }
            .batch-data3{
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


