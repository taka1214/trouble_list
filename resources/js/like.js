window.toggleLike = function () {
  $('.toggle-like').on('click', function (event) {
    event.preventDefault();
    let $button = $(this);
    let postId = $button.data('post-id');
    let isLiked = $button.data('liked');
    let url = isLiked
      ? window.unlikeUrl.replace('__id', postId)
      : window.likeUrl.replace('__id', postId);

    axios
      .post(url)
      .then(function (response) {
        // Update the like count
        $button.find('.badge').text(response.data.likes_count);

        // Toggle the button style and update the data-liked attribute
        if (isLiked) {
          $button.removeClass('btn-success');
          $button.addClass('btn-secondary');
          $button.find('i').css('color', 'silver');
        } else {
          $button.removeClass('btn-secondary');
          $button.addClass('btn-success');
          $button.find('i').css('color', '#c8ad85');
        }
        $button.data('liked', !isLiked);
      })
      .catch(function (error) {
        console.error(error);
      });
  });
};
