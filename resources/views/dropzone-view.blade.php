<!DOCTYPE html>

<html>

<head>

    <title>Upload Multiple Images using dropzone.js and Laravel</title>

    <script src="http://demo.itsolutionstuff.com/plugin/jquery.js"></script>

    <link rel="stylesheet" href="http://demo.itsolutionstuff.com/plugin/bootstrap-3.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">

     <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>

</head>

<body>


<div class="container">

    <div class="row">

        <div class="col-md-12">

            <h1>Upload Multiple Images using dropzone.js and Laravel</h1>

           
			<form action="dropzone/store" files="true" enctype="multipart/form-data" class="dropzone" id="image-upload">
			<input type="hidden" name="_token" value="{{csrf_token()}}">
            <div>

                <h3>Upload Multiple Image By Click On Box</h3>

            </div>

            </form>
             <form action="dropzone/store" enctype="multipart/form-data" method="POST" enctype="multipart/form-data">
             <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="text" id ="firstname" name ="firstname" />
    <input type="text" id ="lastname" name ="lastname" />
    <div class="dropzone" id="myDropzone"></div>
    <button type="submit" id="submit-all"> upload </button>
</form>

        </div>

    </div>

</div>


<script type="text/javascript">

        Dropzone.options.imageUpload = {

            maxFilesize         :       1,
            parallelUploads: 100,
            uploadMultiple: true,
            acceptedFiles: ".jpeg,.jpg,.png,.gif"

        };
        Dropzone.options.myDropzone= {
        	    url: 'dropzone/store',
        	    autoProcessQueue: false,
        	    uploadMultiple: true,
        	    parallelUploads: 5,
        	    maxFiles: 5,
        	    maxFilesize: 1,
        	    acceptedFiles: 'image/*',
        	    addRemoveLinks: true,
        	    init: function() {
        	        dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

        	        // for Dropzone to process the queue (instead of default form behavior):
        	        document.getElementById("submit-all").addEventListener("click", function(e) {
        	            // Make sure that the form isn't actually being sent.
        	            e.preventDefault();
        	            e.stopPropagation();
        	            dzClosure.processQueue();
        	        });

        	        //send all the form data along with the files:
        	        this.on("sendingmultiple", function(data, xhr, formData) {
        	        	formData.append("_token", $('[name=_token').val());
        	            formData.append("firstname", jQuery("#firstname").val());
        	            formData.append("lastname", jQuery("#lastname").val());
        	        });
        	    }
        	};

</script>


</body>

</html>