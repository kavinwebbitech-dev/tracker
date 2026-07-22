<h5>Point Name: {{ $amount_details->name ?? '' }}</h5>

<div class="row">
    <div class="col-md-8">
        <form action="{{ route('client.task.request.comment.update') }}" method="post">
            @csrf
            <div class="row">
                <input type="hidden" name="client_task_id" value="{{ $amount_details->client_task_id ?? '' }}">
                <input type="hidden" name="client_id" value="{{ $amount_details->id ?? '' }}">

                <div class="col-md-6">

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="input-group mb-3">
                            <select class="form-control select2 @error('status') is-invalid @enderror" name="status" onchange="StatusCheck()" id="status_change">
                                <option value="">Select Status</option>
                                <option value="Approved">Approved</option>
                                <option value="Objection ">objection </option>
                            </select>
                        </div>
                        @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                </div>

                <div class="col-md-12">
                    <div class="form-group" style="display: none;" id="description_box">

                        <label class="form-label">User Comment</label>
                        <div class="input-group mb-3">
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
        @if(Auth::user()->id == $comment_details->user_id)
        <div class="row" style="border-bottom: 1px solid #e3e3e3;">
            <p><b>{{ $comment_details->user_details->name }}</b> <span style="font-size: 10px;float: right;margin-top: 7px;">{{ date('d-m-y H:i A', strtotime($comment_details->created_at)) }}</span></p>
            @if(isset($comment_details) && $comment_details->comments)
                {!! $comment_details->comments !!}
            @endif
        </div>
        @else
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
</div>

<script type="text/javascript">
    function StatusCheck() {
        var status_change = $("#status_change").val();
        
        if (status_change == "Approved") 
        {
            $("#description_box").hide();
        }
        else
        {
            $("#description_box").show();
        }

    }
</script>