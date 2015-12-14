$(function() {
  return $("#countdown").xxcountdown({
    "callback": function() {
      return location.reload();
    }
  });
});
