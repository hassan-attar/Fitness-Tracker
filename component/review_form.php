<?php

function review_form(){
  return '<form class="review-form">
            <label for="review">Share your thoughts:</label>
            <div class="user-rating">
              <label for="rating">Rating: </label>
            <div class="star-group">
              <label for="star-1">
                <ion-icon name="star-outline" class="star" data-star-val=1></ion-icon>
              </label>
              <label for="star-2">
                <ion-icon name="star-outline" class="star" data-star-val=2></ion-icon>
              </label>
              <label for="star-3">
                <ion-icon name="star-outline" class="star" data-star-val=3></ion-icon>
              </label>
              <label for="star-4">
                <ion-icon name="star-outline" class="star" data-star-val=4></ion-icon>
              </label>
              <label for="star-5">
                <ion-icon name="star-outline" class="star" data-star-val=5></ion-icon>
              </label>
              <input type="radio" name="rate" id="star-1" value="1" />
              <input type="radio" name="rate" id="star-2" value="2" />
              <input type="radio" name="rate" id="star-3" value="3" />
              <input type="radio" name="rate" id="star-4" value="4" />
              <input type="radio" name="rate" id="star-5" value="5" />
            </div>
          </div>
          <textarea name="comment" id="review" class="review-text" placeholder="Tell us about the taste!"></textarea>
          <button type="submit">Submit</button>
        </form>';
}
?>