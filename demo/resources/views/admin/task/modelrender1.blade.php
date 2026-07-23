<h5>Point Name: {{ $amount_details->name ?? '' }}</h5>

<div class="row">
    <div class="col-md-8">
        <form action="{{ route('admin.task.request.comment.update') }}" method="post">
            @csrf
            <div class="row">
                <input type="hidden" name="client_task_id" value="{{ $amount_details->client_task_id ?? '' }}">
                <input type="hidden" name="client_id" value="{{ $amount_details->id ?? '' }}">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <div class="input-group in-bord mb-3">
                            <input type="text" class="form-control @error('category') is-invalid @enderror" name="category" id="category" value="{{ $amount_details->category ?? '' }}">
                        </div>
                        @error('category')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-8"></div>
                            <div class="col-md-4" style="text-align: right;">
                                <input type="checkbox" id="vehicle1" name="amount_type" value="fixed_amount" @if($amount_details->amount_type == "fixed_amount") checked @endif onchange="FixedAmount()">
                                <label for="vehicle1"> Fixed Amount</label><br>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row" id="doller_id" @if($amount_details->amount_type == "fixed_amount") style="display: none;" @else style="display: block;" @endif>
                            <div class="col-md-4">
                                <label class="form-label">Doller <span style="font-size: 10px;">($ Per Hour)</span></label>
                            </div>
                            <div class="col-md-8">
                                <p style="display: none;" id="total_display"> <b>Today Doller Value:</b> <span id="today_doller_amount"></span> <b>Total Doller Value:</b> <span id="total_amount"></span></p>
                            </div>
                        </div>
                        <div class="row" id="amount_id" @if($amount_details->amount_type == "fixed_amount") style="display: block;" @else style="display: none;" @endif>
                            <div class="col-md-4">
                                <label class="form-label">₹ Rupees</label>
                            </div>
                        </div>
                        <div class="input-group in-bord mb-3">
                            <input type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" id="amount" value="{{ $amount_details->amount ?? '' }}" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" onkeyup="DollerAmount()">
                        </div>
                        @error('amount')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group" id="time_and_hour" @if($amount_details->amount_type == "fixed_amount") style="display: none;" @else style="display: block;" @endif>
                        <label class="form-label">Estimate Time</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group in-bord mb-3">
                                    <input type="text" class="form-control @error('estimate_time') is-invalid @enderror" name="estimate_time" id="estimate_time" value="{{ $amount_details->estimate_time ?? '' }}">
                                </div>
                                @error('estimate_time')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="input-group in-bord mb-3">
                                        <select class="form-control @error('estimate_type') is-invalid @enderror" name="estimate_type" id="estimate_type">
                                            <option value="Hours">Hours</option>
                                            <option value="Minutes">Minutes</option>
                                        </select>
                                        <!-- <span class="input-group in-bord-text"><i class="ti-user"></i></span> -->
                                    </div>
                                    @error('estimate_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Admin Comment</label>
                        <div class="input-group in-bord mb-3">
                            <textarea name="editor1" id="editor1">
                                
                            </textarea>
                        </div>
                        @error('editor1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-4" style="border-left: 1px solid #dee2e6;height: 500px;overflow: overlay;">
        
        @if($amount_details->client_comment_details)
        @foreach($amount_details->client_comment_details as $key => $comment_details)
        <div class="row" style="border-bottom: 1px solid #e3e3e3;">
            <div class="col-md-6">
                <b>{{ $comment_details->user_details->name }}</b>
            </div>
            <div class="col-md-6">
                <span style="font-size: 10px;float: right;margin-top: 7px;">{{ date('d-m-y H:i A', strtotime($comment_details->created_at)) }}</span>
            </div>
            <div class="col-md-10">
                @if(isset($comment_details) && $comment_details->comments)
                    {!! $comment_details->comments !!}
                @endif
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.projects.task.estimate.comment.delete', $comment_details->id) }}" class="delete btn btn-danger" data-confirm="Are you Sure Delete this Comment?" style="padding: 2px 4px;float: right;"><i class="ti-trash text-white"></i></a>
            </div>
        </div>
        @endforeach
        @endif

    </div>

    <?php
        $user_details = \App\Models\User::where('id', 1)->first();
        $today_doller = $user_details->rupees_amount;
    ?>

    <script type="text/javascript">
        function DollerAmount() {

            var amount = $("#amount").val();
            var doller_amount = "{{ $today_doller }}";

            var total_amount = amount * doller_amount;

            let number = total_amount;
            let rounded = number.toFixed(2);


            $("#total_amount").html(rounded);
            $("#today_doller_amount").html(doller_amount);
            $("#total_display").show();

        }

        function FixedAmount()
        {
            if ($('#vehicle1').prop('checked')) {
                $("#doller_id").hide();
                $("#amount_id").show();
                $("#time_and_hour").hide();
            }
            else
            {
                $("#doller_id").show();
                $("#amount_id").hide();
                $("#time_and_hour").show();
            }
        }

    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            var deleteLinks = document.querySelectorAll('.delete');

            for (var i = 0; i < deleteLinks.length; i++) {
              deleteLinks[i].addEventListener('click', function(event) {
                  event.preventDefault();

                  var choice = confirm(this.getAttribute('data-confirm'));

                  if (choice) {
                    window.location.href = this.getAttribute('href');
                  }
              });
            }
        });
    </script>

</div>
