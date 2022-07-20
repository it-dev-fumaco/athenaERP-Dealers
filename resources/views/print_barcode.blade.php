<html>
    <!-- License:  LGPL 2.1 or QZ INDUSTRIES SOURCE CODE LICENSE -->
    <head><title>QZ Print Plugin</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="{{ asset('/3rdparty/deployJava.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('/3rdparty/jquery-1.10.2.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/qz-websocket.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/QZ-PRINT.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/3rdparty/html2canvas.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/3rdparty/jquery.plugin.html2canvas.js') }}"></script>

    <style>
        #deployJavaPlugin{
            display:none;
        }

        table tr td{
            border:0px;
        }

        table{
            border:0px;
        }
    </style>
</head>

<body id="qz-status" bgcolor="#FFF380">
    <div style="margin: 0 1em;display:none;">
        <h1 id="title" style="margin:0;">Print Barcode</h1>
    </div>
    <table border="1px" cellpadding="5px" cellspacing="0px">
        <tr>
            <td valign="top"><h2>PRINT</h2>
                <input type="button" onClick="findPrinter()" value="PRINT">
                <br />
                <input id="printer" type="text" value="zebra" size="15"><br />
                <strong>{{ $item_details->description }}</strong>
                <input id="barcode" type="hidden" value="{{ $item_details->name }}" size="15"><br />
                <p>QUANTITY</p><input id="quantity" type="integer" value="1" size="15"/>
            </td>
        </tr>
        <tr>
            <td>
                <p>COLUMN</p><input type="number" id="column" type="integer" value="4" size="15" min="1" max="10" />
                <p>ROW</p><input type="number"  min="1" max="10"id="rows" type="integer" value="1" size="15"/>
            </td>
            <td>
                <p>Y DISTANCE</p><input type="number" id="y_distance" type="integer" value="80" size="15" min="10" max="1000" />
                <p>X DISTANCE</p><input type="number"  min="10" max="1000"id="x_distance" type="integer" value="120" size="15"/>
            </td>
        </tr>
        <tr>
            <td><button onClick="useDefaultPrinter()">Set to Default</button></td>
        </tr>
    </table>
    <p id="testing"></p>
</body>

<canvas id="hidden_screenshot" style="display:none;"></canvas>

</html>

