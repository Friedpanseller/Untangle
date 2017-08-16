function rateCourse(ID, field, color) {
    if(color == "green") {
        $("#courseRatings").css("border", "5px solid #4db53c");
        $("#courseRatings").css("color", "#4db53c");
    } else if(color == "red") {
        $("#courseRatings").css("border", "5px solid crimson");
        $("#courseRatings").css("color", "crimson");
    } else if(color == "blue") {
        $("#courseRatings").css("border", "5px solid #307bbb");
        $("#courseRatings").css("color", "#307bbb");
    }
    
    $("#courseRatings").fadeIn(300);
    $("#courseRatings").html(`
        <center>
            <br />
            <br />
            <span style="font-size: 30px;" class="noselect">Submit a Rating for ` + ID + `</span>
            <br />
            <span style="font-size: 20px;" class="noselect">` + field + `</span>
            <br />
            <br />
            <br />
            <br />
            <input id="ratingsSlider" type="range">
        </center>
        <button 
            id="submitReview" 
            onclick="submitRating('` + ID + `')" 
            class="loginButton btnBlack" 
            style="position: absolute; bottom: 30px; right: 30px;"
        >
            Submit Review
        </button>
    `);
    
    //From https://codepen.io/andreruffert/pen/mJgvmq
    $('#ratingsSlider')
      .rangeslider({
        polyfill: false,
        onInit: function() {
          var $handle = $('.rangeslider__handle', this.$range);
          updateHandle($handle[0], this.value);
        }
      })
      .on('input', function(e) {
        var $handle = $('.rangeslider__handle', e.target.nextSibling);
        updateHandle($handle[0], this.value);
      });

}

function updateHandle(el, val) {
    el.textContent = val;
}