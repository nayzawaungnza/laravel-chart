<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Chart JS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/argon-design-system-free@1.2.0/assets/css/argon-design-system.min.css">

</head>
<body>
    <div class="container pt-5">
        <div class="row">
            <div class="col-12">
                <div class="card card-body">
                    @if (session()->has('success'))
                    <div class="alert alert-success fade show" role="alert">
                        <strong>Data Store </strong> {{ session('success') }}
                        
                      </div>
                    @endif
                    <form action="{{ route('wage#store') }}" method="post" class="d-flex justify-content-between">
                        @csrf
                        <div class="from-group">
                            <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif text-dark w-100" value="{{ old('title') }}" placeholder="အကြောင်းအရာ" name="title" id="">
                           
                        </div>
                        <div class="from-group">
                            <input type="number" class="form-control @if($errors->has('price')) is-invalid @endif text-dark w-100" value="{{ old('price') }}" placeholder="price" name="price" id="">
                           
                        </div>
                        <div>
                            <input type="date" class="form-control @if($errors->has('date')) is-invalid @endif text-dark w-100" value="{{ old('date') }}" placeholder="" name="date" id="">
                        </div>
                       <div>
                            <select name="type" class="form-control @if($errors->has('type')) is-invalid @endif w-100">
                                <option value="in" @selected(old('type') == 'in')> ဝင်ငွေ </option>
                                <option value="out" @selected(old('type') == 'out')> ထွက်ငွေ </option>
                            </select>
                       </div>
                        <input type="submit" class="btn btn-success" value="စာရင်းသွင်းမယ်">
                    </form>
                </div>
            </div>

            <div class="col-6">
                <div class="card card-body mt-3">
                    <ul class="list-group">
                        @if ($data)
                            @foreach ($data as $wage)
                            <li class="list-group-item d-flex justify-content-between">
                                <div class="text text-dark">
                                    {{ $wage->title }} <br>
                                    <small class="text-muted">{{ $wage->date }}</small>
                                </div>
                                <div class="text @if ($wage->type == 'in') text-success @elseif($wage->type == 'out') text-danger @endif">
                                    @if ($wage->type == 'in')+@elseif($wage->type == 'out')-@endif{{ $wage->price }} ကျပ်
                                </div>
                            </li>
                            @endforeach
                            
                        @else
                            
                        @endif
                        
                       
                    </ul>
                </div>
            </div>
            <div class="col-6">
                <div class="card card-body mt-3">
                    <div class="d-flex justify-content-between">
                        <h5>Chart</h5>
                        <div>
                            <small class="text-success mr-3">ယနေ့ ဝင်ငွေ : +{{ $today_income }} ကျပ် </small>
                            <small class="text-danger border-left pl-3">ယနေ့ ထွက်ငွေ : -{{ $today_outcome }} ကျပ် </small>
                        </div>
                    </div>
                    <hr>
                    <canvas id="inout"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        const ctx = document.getElementById('inout');

        new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($day_arr),
            datasets: [
                {
                label: 'ဝင်ငွေ',
                data: @json($income_amount),
                borderWidth: 1,
                backgroundColor:'#2dce89'
                },
                {
                label: 'ထွက်ငွေ',
                data: @json($outcome_amount),
                borderWidth: 1,
                backgroundColor:'#f5365c'
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
        });

    </script>
</body>
</html>