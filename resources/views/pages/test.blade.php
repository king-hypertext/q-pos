{{-- <html>

<head> <!-- Include jQuery and select 2 plugin files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
@php
    use Illuminate\Support\Facades\DB;
    $products = DB::table('products')
        ->where('quantity', '>', 0)
        ->get(['name']);
    $empty_q = DB::table('products')
        ->where('quantity', '<', 1)
        ->count('name');
    $empty_p = DB::table('products')->get(['*']);
@endphp

<body> <!-- Create a form with a select element and two input elements -->
    <form id="myForm">
        <div id="dynamicFields">
            <div class="fieldWrapper"> <label for="select1">Select an option:</label>
                <select id="select1" name="select1" class="select2">
                    <option selected disabled> Select Product </option>
                    @foreach ($products as $product)
                        <option value="{{ $product->name }}">
                            {{ $product->name }} </option>
                    @endforeach
                </select>
                <label for="input1">Input 1:</label>
                <input id="input1" name="input1" type="text" readonly>
                <label for="input2">Input 2:
                </label> <input id="input2" name="input2" type="number">
                <label for="result">Result:</label>
                <input id="result" name="result" type="text" readonly> <input type="button" class="remove"
                    value="-">
            </div>
        </div>
        <input type="button" id="add" value="+">
        <input type="submit" id="submit" value="Submit">
    </form>

    <script>
        $(document).ready(function() { // Initialize the select 2 plugin with AJAX settings 
            $(".select2").select2();
            // Add a change event handler for the select element 
            $("#select1").change(function() {
                // Get the selected option value
                var value = $(this).val();
                console.log(value); // Make an AJAX request to fetch the data for input 1 based on the selected option
                $.ajax({
                    url: "/product/price?q=" + value, // The URL to fetch the data from the server
                    // dataType: 'json',
                    success: function(data) {
                        // Set the value of input 1 to the data returned by the server
                        $("#input1").val(data.data[0].price); // Calculate the result by multiplying input 1 and input 2 
                        var result = Number.parseFloat(data.data[0].price) * $("#input2").val(); // Set the value of the result input to the calculated value 
                        $("#result").val(result);
                    }
                });
            });
            // Add a change event handler for input 2 
            $("#input2").change(function() { // Get the value of input 2 
                var value = $(this).val(); // Calculate the result by multiplying input 1 and input 2 
                var result = $("#input1").val() * value;
                // Set the value of the result input to the calculated value 
                $("#result").val(result);
            });
            // Add a click
            //event handler for the add button 
            $("#add").click(function() {
                // Clone the first field wrapper 
                var newFieldWrapper = $("#dynamicFields .fieldWrapper:first")
                    .clone(); // Clear the values of the cloned inputs
                newFieldWrapper.find("input").val(
                    ""); // Append the cloned field wrapper to the dynamic fields div
                newFieldWrapper.appendTo(
                    "#dynamicFields"); // Re-initialize the select 2 plugin for the cloned select element
                newFieldWrapper.find(".select2").select2();
                // Add achange event handler for the cloned select element 
                newFieldWrapper.find(".select2").change(function() {
                    // Get theselected option value
                    var value = $(this).val();
                    // Make an AJAX request to fetch the data for input 1 based on the selected option 
                    $.ajax({
                        url: "/product/price?q=" + value,
                        // dataType: 'json',
                        success: function(data) {
                            // Set the value of input 1 to the data returned by the server 
                            newFieldWrapper.find("#input1").val(data.data[0].quantity);
                            //Calculate the result by multiplying input 1 and input 2
                            var result = data.data[0].quantity * newFieldWrapper.find("#input2").val(); //Set the value of the result input to the calculated value 
                            newFieldWrapper.find("#result").val(result);
                        }
                    });
                });
                //Add a change event handler for the cloned input 2 
                newFieldWrapper.find("#input2").change(function() {
                    // Get the value of input 2
                    var value = $(this).val();
                    // Calculate the result by multiplying input 1 and input 2 
                    var result = newFieldWrapper.find("#input1").val() * value;
                    // Set the value of the result input to the calculated value
                    newFieldWrapper.find("#result").val(result);
                });
            });
        });
        // Add a click event handler for the remove button
        $(document).on("click", ".remove", function() {
            // Remove the field wrapper of the clicked button
            $(this).parent().remove();
        });
    </script>
</body>

</html> --}}
@extends('layout.layout')
@section('content')
    <div class="container">
        @php
            use Illuminate\Support\Facades\DB;
            $products = DB::table('products')
                ->where('quantity', '>', 0)
                ->get(['name', 'price']);
            // dd($products);
        @endphp

        <h1>Dynamic Form</h1>
        <form id="myForm">
            <div class="row" id="row-1">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="select-1">Select</label>
                        <select class="form-control" id="select-1" name="select[]">
                            <option selected disabled>select Product</option>
                            @foreach ($products as $product)
                                <option id="{{ $product->price }}" value="{{ $product->price }}">{{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="qty-1">Quantity</label>
                        <input type="number" data-qty-input class="form-control" id="qty-1" name="qty[]">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="price-1">Price</label>
                        <input type="number" class="form-control" id="price-1" name="price[]">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="output-1">Total</label>
                        <input type="number" class="form-control" id="output-1" name="output[]" readonly>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group d-none">
                        <label for="delete-1">Delete</label>
                        <button type="button" class="btn btn-danger form-control" id="delete-1">X</button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="add-row">Add Row</button>
            <button type="submit" class="btn btn-success" id="submit">Submit</button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            // Initialize the output value for the first row
            updateOutput(1);

            // Add a new row when the add button is clicked
            $("#add-row").click(function() {
                // Get the index of the last row
                var lastRow = $(".row").last().attr("id").split("-")[1];

                // Increment the index by one
                var newRow = parseInt(lastRow) + 1;

                // Clone the last row and append it to the form
                $(".row").last().clone().appendTo("#myForm");

                // Change the id and name attributes of the new row elements
                $(".row").last().attr("id", "row-" + newRow);
                $(".row").last().find("select").attr("id", "select-" + newRow).attr("name", "select[]");
                $(".row").last().find("input[type='number'][data-qty-input]").attr("id", "qty-" + newRow)
                    .attr("name", "qty[]");
                $(".row").last().find("input[type='text']").attr("id", "price-" + newRow)
                    .attr("name", "price[]");
                $(".row").last().find("input[readonly]").attr("id", "output-" + newRow)
                    .attr("name", "output[]");
                $(".row").last().find("button").attr("id", "delete-" + newRow);

                // Reset the input and price values of the new row
                $("#qty-" + newRow).val("");
                $("#price-" + newRow).val("");

                // Update the output value of the new row
                updateOutput(newRow);
            });

            // Delete a row when the delete button is clicked
            $(document).on("click", "button[id^='delete-']", function() {
                // Get the index of the current row
                var currentRow = $(this).closest(".row").attr("id").split("-")[1];
                // Remove the current row from the form
                $("#row-" + currentRow).remove();
            });

            // Update the output value when the select or input value changes
            $(document).on("keyup", "input[type='number'][data-qty-input]", function() {
                console.log($(this));
                var currentRow = $(this).closest(".row").attr("id").split("-")[1];

                // Update the output value of the current row
                updateOutput(currentRow);
            });
            $(document).on("change", "select", function() {
                // Get the index of the current row
                var currentRow = $(this).closest(".row").attr("id").split("-")[1];

                // Update the output value of the current row
                updateOutput(currentRow);
            });

            // Define a function to update the output value
            function updateOutput(row) {
                // Get the select and input values of the given row
                var selectValue = $("#select-" + row).val();
                var inputValue = $("#qty-" + row).val();

                // Multiply the select and input values
                var outputValue = selectValue * inputValue;

                // Set the output value of the given row
                $("#output-" + row).val(outputValue);
            }

            // Prevent the default form submission behavior
            $("#myForm").submit(function(e) {
                e.preventDefault();
                // $("select").value = 
            });
        });
    </script>
@endsection
