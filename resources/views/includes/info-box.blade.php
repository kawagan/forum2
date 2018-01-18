 @if(session()->has('m'))
    <?php $a=[]; $a=session()->pull('m'); ?>
        <div class="row">
            <div class="alert alert-{{$a[0]}}">
                {{$a[1]}}
            </div>
        </div>
    @endif