@extends('old_design.main')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Your Form Header
                    <button id="addInput" class="btn btn-sm btn-danger float-right">Add Input</button>
                </div>

                <div class="card-body">
                    <div id="input-container" class="row">
                        <!-- Input fields will be added dynamically here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        // Counter to keep track of the number of input fields
        var inputCount = 0;

        // Function to add a new input container with various fields
        function addInputContainer() {
            inputCount++;
            var inputContainer = $('<div class="input-container col-md-12 mb-2">');
            
            // Contractor Name
            var contractorNameField = $('<input type="text" class="form-control mb-2" name="contractor_name_' + inputCount + '" placeholder="Contractor Name">');

            // Contact No
            var contactNoField = $('<input type="text" class="form-control mb-2" name="contact_no_' + inputCount + '" placeholder="Contact No">');

            // Percentage
            var percentageField = $('<input type="text" class="form-control mb-2" name="percentage_' + inputCount + '" placeholder="Percentage">');

            // Percentage
            var remarks = $('<input type="text" class="form-control mb-2" name="remarks_' + inputCount + '" placeholder="Ramarks">');

            // Remove Button
            var removeButton = $('<button class="removeInput btn btn-sm btn-danger mt-2">Remove</button>');

            inputContainer.append(contractorNameField);
            inputContainer.append(contactNoField);
            inputContainer.append(percentageField);
            inputContainer.append(remarks);
            inputContainer.append(removeButton);

            // Add event handler to remove the input field when the "Remove" button is clicked
            removeButton.click(function () {
                inputContainer.remove();
            });

            // Append the new input container to the main container
            $('#input-container').append(inputContainer);
        }

        // Add the first input field
        addInputContainer();

        // Add event handler to the "Add Input" button
        $('#addInput').click(function () {
            addInputContainer();
        });
    });
</script>
@endsection
