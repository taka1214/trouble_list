const likeButtons = document.querySelectorAll('.like-button');

likeButtons.forEach((button) => {
  button.addEventListener('click', async (e) => {
    e.preventDefault();
    const postId = button.dataset.postId;
    const isLiked = button.dataset.liked === 'true';

    const likeUrl = `/api/posts/like/${postId}`;
    const unlikeUrl = `/api/posts/unlike/${postId}`;

    try {
      const response = isLiked
        ? await fetch(unlikeUrl, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
          })
        : await fetch(likeUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
          });

      if (response.ok) {
        button.dataset.liked = !isLiked;
        button.textContent = isLiked ? 'Like' : 'Unlike';
      }
    } catch (error) {
      console.error('Error:', error);
    }
  });
});
