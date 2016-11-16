var canvas = document.getElementById('canvas');
var context = canvas.getContext('2d');
canvas.width = 600;
canvas.height = 600;

function loadImage(url, callback) {
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
        callback();
    });

    img.src = url;
}

function reqListener () {
    var random_stuff = JSON.parse(this.response);

    loadImage(random_stuff.photo_url, function () {
        context.font = '48px sans-serif';
        context.fillText(random_stuff.album_title, 10, 50);
    });
}

var xhr = new XMLHttpRequest();
xhr.addEventListener('load', reqListener);
xhr.open('GET', '/random.php');
xhr.send();
