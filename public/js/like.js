const likeButtons = document.querySelectorAll('.like-button');

likeButtons.forEach((button) => {
  button.addEventListener('click', async (e) => {
    e.preventDefault();
    const postId = button.dataset.postId;
    const isLiked = button.dataset.liked === 'true';

    try {
      const response = isLiked
        ? await fetch(`/api/unlike/${postId}`, {
            method: 'DELETE',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
          })
        : await fetch(`/api/like/${postId}`, {
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
