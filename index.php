<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>โอนเงินออนไลน์</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .container-fluid{
            margin-top:100px;
        }
    </style>
</head>
<body>
    
    <div class="container-fluid">
        <div class="row text-center">
            <div class="col-md-12">
                <img src="img/bank.png" width="100px" />
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-12">
                <h3>ยอดเงินคงเหลือ <span id="amount" style="color:blue;">0.0</span> บาท</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <form id="form">
                    <div class="form-group">
                        <label for="formGroupExampleInput">จำนวนเงิน</label>
                        <input type="text" class="form-control" placeholder="จำนวนเงิน" id="transfer">
                    </div>
                    <div class="form-group">
                        <input type="submit" class="form-control btn-success" value="โอนเงิน">
                    </div>
                </form>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <h6>ผลลัพธ์: <span id="result" style="color:red"></span></h6>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/currency.min.js"></script>
    <script>
        var amount = $("#amount");
        var form = $("#form");
        var transfer = $("#transfer");
        init();
        function init(){
            $.get("getAmount.php", function(data, status){
                var money = currency(data, {  precision: 4 }).format()
                amount.text(money);
            });
        }
        
        form.submit(function(){
            event.preventDefault();
            $.get("submit.php", {
                "money": transfer.val()
            },function(data, status){
                $("#result").text(JSON.parse(data));
                init();
            });
        });

    </script>
</body>
</html>