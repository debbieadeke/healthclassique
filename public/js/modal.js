// Get all images with the class "myImg"
var images = document.querySelectorAll(".myImg");

// Loop through each image and add a click event listener
images.forEach(function(img, index) {
    img.onclick = function(){
        var modalId = "myModal" + index;
        var modal = document.getElementById(modalId);
        var modalImg = document.getElementById("img" + index);
        var captionText = document.getElementById("caption" + index);

        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    };
});

// Get all close buttons
var closeButtons = document.querySelectorAll(".close");

// Loop through each close button and add a click event listener
closeButtons.forEach(function(button, index) {
    button.onclick = function() {
        var modalId = "myModal" + index;
        var modal = document.getElementById(modalId);
        modal.style.display = "none";
    };
});
