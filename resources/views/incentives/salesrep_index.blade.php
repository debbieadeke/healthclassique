@php(extract($data))
@extends('layouts.app-v2')
@section('content-v2')
    <div class="container planner">
        <div class="card text-bg-light">
            <div class="card-body">
                <h3>My Incentive</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a> <i class="fas fa-angle-right"></i></li>
                        <li class="breadcrumb-item active" aria-current="page">My Incentive</li>
                    </ol>
                </nav>
            </div>
        </div>
       <div class="card p-3">
          <div class="card-body">
              <div class="row">
                  <div class="col-md-8">
                      <h5 class="card-title fw-semibold mb-4"> {{$pagetitle}}</h5>
                  </div>
              <div class="col-md-4">
                  <form>
                      <div class="form-group">
                          <label for="quarter"><b>Select Quarter:</b></label>
                          <select class="form-control" id="quarter" name="quarter">
                              @foreach ($quarters as $quarterNum => $quarterData)
                                  <option value="{{ $quarterData['start'] }}" {{ $selectedQuarter == $quarterData['start'] ? 'selected' : '' }}>
                                      {{ $quarterData['start'] }} to {{ $quarterData['end'] }}
                                  </option>
                              @endforeach
                          </select>
                      </div>
                  </form>
              </div>
          </div>
           <div class="row">
               @foreach ($quarterlyData as $quarterNum => $monthlyData)
                   <div class="row">
                       <div id="quarter_{{ $quarterNum }}" class="quarterly-data col-md-12" style="{{ $selectedQuarter == $quarterNum ? '' : 'display:none;' }}">
                           @foreach ($monthlyData as $component => $data )
                               <div class="col-md-12">
                                   <h4>{{ $data['month'] }} Incentive</h4>
                                   <div class="table-responsive">
                                       <table class="table table-responsive table-dash">
                                           <thead class="thead-dark">
                                           <tr>
                                               <th>Component</th>
                                               <th>Weight</th>
                                               <th>Product</th>
                                               <th>Weight</th>
                                               <th>Target</th>
                                               <th>Achieved</th>
                                               <th>%Done</th>
                                               <th>Incentive</th>
                                           </tr>
                                           </thead>
                                           <tbody>
                                           <tr>
                                               <td>KPI's</td>
                                               <td >10%</td>
                                               <td class="font-weight-bold">Key Performance indicators</td>
                                               <td>100%</td>
                                               <td>{{$data['kpiTarget']}}</td>
                                               <td>{{$data['kpiAchieved']}}</td>
                                               <td>{{$data['kpiDone']}}%</td>
                                               <td>{{$data['kpiIncentive']}}</td>
                                           </tr>
                                           <tr>
                                               <td rowspan="4">Individual</td>
                                               <td rowspan="4">90%</td>
                                               <td>{{ implode('/', $data['tier1']) }}</td>
                                               <td>40%</td>
                                               <td>{{ number_format($data['tier1TargetSum'], 2, '.', ',') }}</td>
                                               <td>{{ number_format($data['tier1AchievedSum'], 2, '.', ',') }}</td>
                                               <td>{{$data['tier1Done']}}%</td>
                                               <td>{{ number_format($data['tier1Incentive'], 2, '.', ',') }}</td>
                                           </tr>
                                           <tr>
                                               <td>{{ implode('/', $data['tier2']) }}</td>
                                               <td>25%</td>
                                               <td>{{ number_format($data['tier2TargetSum'], 2, '.', ',') }}</td>
                                               <td>{{ number_format($data['tier2AchievedSum'], 2, '.', ',') }}</td>
                                               <td>{{$data['tier2Done']}}%</td>
                                               <td>{{ number_format($data['tier2Incentive'], 2, '.', ',') }}</td>
                                           </tr>
                                           <tr>
                                               <td>{{ implode('/', $data['tier3']) }}</td>
                                               <td>20%</td>
                                               <td>{{ number_format($data['tier3TargetSum'], 2, '.', ',') }}</td>
                                               <td>{{ number_format($data['tier3AchievedSum'], 2, '.', ',') }}</td>
                                               <td>{{$data['tier3Done']}}%</td>
                                               <td>{{ number_format($data['tier3Incentive'], 2, '.', ',') }}</td>
                                           </tr>
                                           <tr>
                                               <td>Total Individual Territory</td>
                                               <td>15%</td>
                                               <td>{{ number_format($data['individualTarget'], 2, '.', ',') }}</td>
                                               <td>{{ number_format($data['individualAchieved'], 2, '.', ',') }}</td>
                                               <td>{{ number_format($data['individualDone'], 0) }}%</td>
                                               <td>{{ number_format($data['individualIncentive'], 2, '.', ',') }}</td>
                                           </tr>
                                           <tr>
                                               <td colspan="7" class="text-end" style="border: none !important;"><h4>Total Incentive Earned:</h4></td> <!-- Merging cells with colspan -->
                                               <td> {{ number_format($data['totalIncentive'], 2, '.', ',') }}</td> <!-- Adding "Total Incentive" to the last column -->
                                           </tr>
                                           </tbody>

                                       </table>
                                   </div>
                               </div>
                           @endforeach
                               <?php
                               $finalIncentive = ($averagePercentagePerQuarter[$quarterNum] > 90) ? $totalIncentivePerQuarter[$quarterNum] : 0;
                               ?>
                           <h4 class="text-end text-danger">Note Total Quarter Achievement must be above 90%</h4>
                           <!-- Total incentive earned -->
                           <div class="text-end mb-2">
                               <h4><b>Total Quarter Incentive Earned:</b> {{ number_format($totalIncentivePerQuarter[$quarterNum], 2, '.', ',') }}</h4>
                           </div>

                           <!-- Average individual incentive -->
                           <div class="text-end mb-2">
                               <h4><b>Average  Quarter Achievement(%):</b> {{ number_format($averagePercentagePerQuarter[$quarterNum], 0, '.', ',')}}%</h4>
                           </div>

                           <!-- Final incentive -->
                           <div class="text-end mb-2">
                               <h4><b>Awarded Incentive:</b> {{ number_format($finalIncentive, 2, '.', ',') }}</h4>
                           </div>
                       </div>
                   </div>
           </div>
       </div>
        @endforeach
    </div>
    </div>
    <style>
        table {
            border-collapse: collapse;
            border: 2px solid #000 !important; /* Thick black border */
            width: 100%;
        }

        th, td {
            border: 2px solid #000 !important;; /* Thin black border */
            padding: 8px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2; /* Alternate row background color */
        }
    </style>
    <script>
        document.getElementById('quarter').addEventListener('change', function() {
            var selectedQuarter = this.value.trim(); // Trim leading and trailing whitespace
            console.log(selectedQuarter);
            var quarterlyDataDivs = document.querySelectorAll('.quarterly-data');
            console.log(quarterlyDataDivs);
            quarterlyDataDivs.forEach(function(div) {
                // Extract the quarter number from the div ID and trim any whitespace
                var quarterNum = div.id.split('_')[1].trim();
                console.log(quarterNum);
                if (quarterNum === selectedQuarter) {
                    div.style.display = 'block'; // Show the selected div
                } else {
                    div.style.display = 'none'; // Hide other divs
                }
            });
        });
    </script>
@endsection
