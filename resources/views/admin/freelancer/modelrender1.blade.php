<h5>Point Name: {{ $amount_details->name ?? '' }}</h5>

<div class="row">
    <div class="col-md-8">
        <form action="{{ route('admin.freelancer.request.comment.update') }}" method="post">
            @csrf
            <div class="row">
                <input type="hidden" name="client_task_id" value="{{ $amount_details->client_task_id ?? '' }}">
                <input type="hidden" name="client_id" value="{{ $amount_details->id ?? '' }}">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">

                        <label class="form-label">User Comment</label>
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
            </div>

            <div class="row">

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
               <a href="javascript:void(0);" onclick="deleteProject('{{ route('admin.projects.task.estimate.comment.delete', $comment_details->id) }}')" class="delete btn btn-danger" data-confirm="Are you Sure Delete this Comment?" style="padding: 2px 4px;float: right;"><i class="ti-trash text-white"></i></a>
            </div>
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