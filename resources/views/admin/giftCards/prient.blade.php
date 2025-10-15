@inject('settings', 'App\Models\Contact')
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/bootstrap.css')}}">
    </head>
<body>

<style>
@media print {
    .hideOnprint {
        display: none;
    }
    .hideOnScreen {
        display: block;
    }
    @page {
    margin:0
}
}

@media screen {
    .hideOnprint {
        display: block;
    }
    .hideOnScreen {
        display: none;
    }
}
.card{
    width: 750px;
    height: 600px;
    text-align: center;
}
</style>

<div class="container">
    <div class="row justify-content-start" style="margin-top:42px">
        <div class=" col-md-4 offset-md-4" style="">
            <img style="height: 150px; width:100%" src="{{asset('public/Front/img/Header-Gift-Card-TicketRequestForm-scaled.jpg')}}">
            <h3 style="text-align: center; color:rgb(9, 87, 87); margin-top:28px"> كارت تهنئة</h3>
            <div style="text-align: center;">
                <img style="width: 140px ; " class="card-img-top" src="{{asset('public/'.$giftCards->qr_image)}}" alt="Card image cap">
            </div>
            <div class="card-body" style="text-align: center;">
              <h5 class="card-title">{{$giftCards->receiver}}</h5>
              <p>{{$giftCards->message}}</p>
              @if($giftCards->type ==1)<span>اخوك : {{App\Models\Client::where('id',$giftCards->client_id)->first()->name}}</span>
              @else<span> لا يرغب في اظهار اسمة</span>@endif
              <button onclick="window.print()" class="print hideOnprint" id="prient">Print this page</button>
              

            </div>
            <img style="height: 150px; width:100%" src="{{asset('public/Front/img/holiday-shop-by-brand-header.jpg')}}">
        </div>
    </div>
    
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
{{-- <script>
    $(function(){
        $("#prient").click(function(){
            var id = $('#prient').val();
            $("#prient").hide();
        });
    });
</script> --}}
{{-- <script>
    function myFunction() {
        $("#prient").click(function(){
        // var id = $('#prient').val();
        $("#prient").hide();
        });
    }
  
</script> --}}

<script>
    $('#date-picker-exchange').pickadate({
  monthsFull: ['يناير', 'فبراير', '	مارس', '	أبريل/إبريل', 'أيار', 'حزيران', 'تموز', '	آب', 'أيلول', 'تشرين الأول', 'تشرين الثاني', 'كانون الأول'],
  monthsShort: ['يناير', 'فبراير', '	مارس', '	أبريل/إبريل', 'أيار', 'حزيران', 'تموز', '	آب', 'أيلول', 'تشرين الأول', 'تشرين الثاني', 'كانون الأول'],
  weekdaysFull: ['الأحد' ,'السبت' ,'الجمعه', 'الخميس', 'الأربعاء', 'الثلاثاء', 'الأثنين'],
  weekdaysShort: ['الأحد' ,'السبت' ,'الجمعه', 'الخميس', 'الأربعاء', 'الثلاثاء', 'الأثنين'],
  today: 'اليوم',
  clear: 'اختيار واضح',
  close: 'إلغاء',
  formatSubmit: 'yyyy/mm/dd'
});
</script>

</body>
</html>


