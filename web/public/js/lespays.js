$(document).ready(function() {
    var audioElement = document.createElement('audio');
    audioElement.setAttribute('src', upload_url + '/langue/wolof.mp3');
    
    audioElement.addEventListener('ended', function() {
        this.play();
    }, false);

    $('#playwolof').click(function() {
        audioElement.play();
    });
    
    $('#pausewolof').click(function() {
        audioElement.pause();
    });
    
});

$(document).ready(function() {
    var audioElement = document.createElement('audio');
    audioElement.setAttribute('src', upload_url + '/langue/arabe.mp3');
    
    audioElement.addEventListener('ended', function() {
        this.play();
    }, false);

    $('#playarabe').click(function() {
        audioElement.play();
    });
    
    $('#pausearabe').click(function() {
        audioElement.pause();
    });
    
});

$(document).ready(function() {
    var audioElement = document.createElement('audio');
    audioElement.setAttribute('src', upload_url + '/langue/more.mp3');
    
    audioElement.addEventListener('ended', function() {
        this.play();
    }, false);

    $('#playmore').click(function() {
        audioElement.play();
    });
    
    $('#pausemore').click(function() {
        audioElement.pause();
    });
    
});

$(document).ready(function() {
    var audioElement = document.createElement('audio');
    audioElement.setAttribute('src', upload_url + '/langue/dioula.mp3');
    
    audioElement.addEventListener('ended', function() {
        this.play();
    }, false);

    $('#playdioula').click(function() {
        audioElement.play();
    });
    
    $('#pausedioula').click(function() {
        audioElement.pause();
    });
    
});