function deletePost(e) {
  'use strict';
  if (confirm('Are you sure?')) {
    document.getElementById('delete_' + e.dataset.id).submit();
    return true
  } else {
    return false;
  }
}