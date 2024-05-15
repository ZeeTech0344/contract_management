@extends("new_design.layout.layout")




@section("content")
<section class="invoice p-3">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header">
          <i class="fas fa-globe"></i> All City Technical Services LLC
          <small class="float-right">Date: {{   date_format(date_create($data[0]->created_at),"d-M-Y") }}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
       <p style="font-size:20px;padding:0; margin:0;font-weight:bolder;">Contractor</p>
        <address>
          <strong style="text-decoration: underline">All City Technical Services LLC</strong><br>
          Tel No 04-4458546<br>
          PO Box 294850 Dubai<br>
          Email: info@allcitydubai.com<br>
          www.allcitydubai.com
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <p style="font-size:20px;padding:0; margin:0;font-weight:bolder;">Client</p>
        <address>
          <strong>{{$data[0]->getOneRecordClient->name}}</strong><br>
          {{$data[0]->getOneRecordClient->address}}<br>
          Phone: {{$data[0]->getOneRecordClient->phone_no}}<br>
        </address>
      </div>

     
      
    </div>
    <div class="text-right p-3" style="font-size: 20px;">
        <b> Quotation# {{$data[0]["invoice_no"]}}</b>
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Sr#</th>
            <th>Description</th>
            <th class="text-center">Quantity</th>
            <th class="text-center">Unit Price</th>
            <th class="text-center">Subtotal</th>
          </tr>
          </thead>
          <tbody>
            @php
                $sr=1;
                $total= 0;
            @endphp
          @foreach ($data as $get_data)
              <tr>
                <td>{{$sr++}}</td>
                <td>{{$get_data->head}}</td>
                <td class="text-center">{{$get_data->quantity}}</td>
                <td class="text-center">{{number_format($get_data->amount)}}</td>
                <td class="text-center">{{number_format($get_data->total)}}</td>
              </tr>
              @php
                  $total = $total + $get_data->total;
              @endphp
          @endforeach
          <tr>
            <td colspan="4" class="text-right"><b>Grand Total</b></td><td class="text-center">{{ number_format($total)}}</td>
          </tr>
          <tr>
           <td colspan="5" id="amount_in_word" style="text-transform: uppercase; font-weight:bold;"></td>
          </tr>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->


    <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">
         

          <p class="text-muted well well-sm shadow-none" style="margin-top: 10px; color:red">
            <p style="color:red">Note: This is computer generated document.<br>No Signature required on our part.
            Please issue the check in favor of All City Technical Services L.L.C.</p>

          </p>
        </div>
        
        <!-- /.col -->
      </div>
      <div class="row no-print">
        <div class="col-12">
          <a href="#" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
      

          @php
            $status = $data[0]->status;
          @endphp
      

          <button type="button"  id="quotation_button" data-id={{$status}} class="btn float-right mr-1 {{$data[0]->status == 0 ? 'btn-danger' : "btn-success"}}">
            {{$data[0]->status == 0 ? 'Not Approved' : "Approved"}}
          </button>

         

         


          {{-- <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
            <i class="fas fa-download"></i> Generate PDF
          </button> --}}
        </div>
      </div>
  </section>

  @endsection

  @section("script")


  <script>




    var invoice_and_client_id = "<?php echo $invoice_data_for_approval; ?>";


    $("#quotation_button").on("click", function(){
      var status = $("#quotation_button").data("id");
      var data = {
        status : status,
        data_for_update: invoice_and_client_id
      }

      $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ url('update-quotation-status') }}",
                        type: "POST",
                        data: {data},
                        dataType: "json",
                        success: function(data) {
                          
                            console.log(data);

                            
                            $("#quotation_button").data("id", data);

                            if(data == 1){
                              $("#quotation_button").addClass("btn-success");
                              $("#quotation_button").text("Approved");
                              $("#quotation_button").removeClass("btn-danger");
                              successAlert("Quotation Approved Successfully!");
                            }else if(data == 0){
                              $("#quotation_button").removeClass("btn-success");
                              $("#quotation_button").addClass("btn-danger");
                              $("#quotation_button").text("Not Approved");
                              errorAlert("Quotation UnApproved!");
                            }
                           
                          
                        },
                        error: function(data) { 

                        }
                    })

    })



    
   function numberToWords(number) {
    const units = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    const teens = ['eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
    const tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

    const convertLessThanOneThousand = (num) => {
        if (num === 0) {
            return '';
        } else if (num < 10) {
            return units[num];
        } else if (num < 20) {
            return teens[num - 11];
        } else if (num < 100) {
            return tens[Math.floor(num / 10)] + ' ' + units[num % 10];
        } else {
            return units[Math.floor(num / 100)] + ' hundred ' + convertLessThanOneThousand(num % 100);
        }
    };

    if (number === 0) {
        return 'zero only';
    }

    const billion = Math.floor(number / 1000000000);
    const million = Math.floor((number % 1000000000) / 1000000);
    const thousand = Math.floor((number % 1000000) / 1000);
    const remainder = number % 1000;

    let result = '';

    if (billion) {
        result += convertLessThanOneThousand(billion) + ' billion ';
    }

    if (million) {
        result += convertLessThanOneThousand(million) + ' million ';
    }

    if (thousand) {
        result += convertLessThanOneThousand(thousand) + ' thousand ';
    }

    if (remainder) {
        result += convertLessThanOneThousand(remainder);
    }

    return 'Total Amount (In Words): ' + result + ' only';
}

// Example usage:

var total = "<?php echo $total ?>";

const words = numberToWords(total);
var amount_in_word = $("#amount_in_word").text(words);
console.log(words);  // Output: "one thousand three hundred fifty-five only"


  </script>

  @endsection