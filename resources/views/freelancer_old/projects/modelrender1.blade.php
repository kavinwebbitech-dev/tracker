<h5>Point Name: {{ $amount_details->name ?? '' }}</h5>

<div class="row">
    <div class="col-md-8">
        <form action="{{ route('freelancer.task.request.comment.update') }}" method="post">
            @csrf
            <div class="row">
                <input type="hidden" name="client_task_id" value="{{ $amount_details->client_task_id ?? '' }}">
                <input type="hidden" name="client_id" value="{{ $amount_details->id ?? '' }}">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label">Please Enter the Comment</label>
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
        @if($comment_details->comments)
        <div class="row" style="border-bottom: 1px solid #e3e3e3;">
            <p><b>{{ $comment_details->user_details->name }}</b> <span style="font-size: 10px;float: right;margin-top: 7px;">{{ date('d-m-y H:i A', strtotime($comment_details->created_at)) }}</span></p>
            @if(isset($comment_details) && $comment_details->comments)
                {!! $comment_details->comments !!}
            @endif
        </div>
        @endif
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

    </script>

    

</div>
