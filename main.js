var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
canvas.width = 600;
canvas.height = 600;

function loadImage(url) {
    var img = new Image();

    img.addEventListener('load', function() {

        var offSetX = 0;
        var offSetY = 0;

        var ratio = Math.max(canvas.width / img.width, canvas.height / img.height);
        img.width = img.width * ratio;
        img.height = img.height * ratio;

        if (img.width > img.height) {
            // Landscape
            // Set image height to fill canvas, crop off left/right
            offSetX = -((img.width / 2) - (canvas.width / 2));
        }

        if (img.width < img.height) {
            // Portrait
            // Set image width to fill canvas, crop off top/bottom
            offSetY = -((img.height / 2) - (canvas.height / 2));
        }

        context.drawImage(this, offSetX, offSetY, img.width, img.height);
    });

    img.src = url;
}

function reqListener () {
    var random_stuff = JSON.parse(this.response);
    console.log(random_stuff);
    loadImage(random_stuff.photo_url);
}

var xhr = new XMLHttpRequest();
xhr.addEventListener('load', reqListener);
xhr.open('GET', '/random.php');
xhr.send();
