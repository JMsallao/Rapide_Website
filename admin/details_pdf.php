
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url("");
            margin: 20px;
            margin-right: 10%;
            padding: 0;
            display: flex;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            display:flex;
            align-items: center;
        }

        .header .logo {
            background-color: teal;
            height: 25px;
            width: 100%;
            justify-content: center;
            padding: 2px 2px 0px 0px;

        }
        .date{
            transform: translate(430px, -40px);
            position: absolute;
            font-size: 13px;
            color: gray;
    
        }
        .header .title {
            flex: 3;
            text-align: center;
            font-size: 23px;
            font-weight: bold;
            color: #ffffff;
            margin: 12px;
            height: 25px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            display: flex;
        }

        .divider {
            border-top: 1px solid #000;
            margin:50px 0;
            margin-bottom: 20px;
            margin-top: 10px;
            
        }
        .label{
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
            </div>
            <div class="title">A.C. Tech</div>
        </div>
        <div class="date">
            <div class="label">Date: <?=$user['created_at']?></div>
        </div>

        <div class="section-title">Customer Details</div>
        <div class="content-item">
            <div class="label">Name: <?=$user['name']?></div>
        </div>

        <div class="divider"></div>

        <div class="section-title">Contact Details</div>
        <div class="content-item">
            <div class="label">Contact Number: <?=$user['contact_no']?></div>
        </div>
        <div class="content-item">
            <div class="label">Address: <?=$user['address']?></div>
        </div>
        <div class="divider"></div>

        <div class="section-title">Booking Details</div>
        <div class="content-item">
            <div class="label">Service: <?=$user['service']?></div>
        </div>
        <div class="content-item">
            <div class="label">Aircon: <?=$user['aircon']?></div>
        </div>
        <div class="content-item">
            <div class="label">Technology: <?=$user['technology']?></div>
        </div>
        <div class="content-item">
            <div class="label">Brand: <?=$user['brand']?></div>
        </div>
        <div class="content-item">
            <div class="label">Date: <?=$user['date']?></div>
        </div>
        <div class="content-item">
            <div class="label">Time: <?=$user['time']?></div>
        </div>
        <div class="content-item">
            <div class="label">No of units: <?=$user['num_units']?></div>
        </div>
    </div>
</body>
</html>
