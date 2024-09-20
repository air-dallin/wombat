// const loader = document.getElementById("preloader");
// window.addEventListener("load", function () {
//   if (loader) {
//     document.body.classList.add("loaded");
//   }
//   setTimeout(() => {
//     loader.style.display = "none";
//     document.body.classList.remove("loaded");
//   }, 3500);
// });

const swiper = new Swiper(".swiperBanner", {
  // Optional parameters
  direction: "horizontal",
  loop: true,
  // navigationDisabledClass: 'swiper-button-disabled',
  slidesPerView: 1,
  grabCursor: true,
  autoplay: {
    delay: 3000,
  },
  // If we need pagination
  // pagination: {
  //   el: ".swiper-pagination",
  // },

  // Navigation arrows
  // navigation: {
  //   nextEl: ".swiper-button-next",
  //   prevEl: ".swiper-button-prev",
  // },

  // And if we need scrollbar
  // scrollbar: {
  //   el: ".swiper-scrollbar",
  // },
});
// Our Products
var swiperProducts = new Swiper(".swiperProducts", {
  slidesPerView: 4,
  // loop: true,
  // centeredSlides: true,
  spaceBetween: 30,
  grabCursor: true,
  autoplay: {
    delay: 3000,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    // desktop >= 991
    320: {
      slidesPerView: 2
    },
    992: {
      slidesPerView: 4,
    }
  }
});

//  Services
var swiperServices = new Swiper(".swiperServices", {
  slidesPerView: 2,
  loop: true,
  // centeredSlides: true,
  spaceBetween: 30,
  grabCursor: true,
  autoplay: {
    delay: 3000,
  },
  // navigation: {
  //   nextEl: ".swiper-button-next",
  //   prevEl: ".swiper-button-prev",
  // },
  breakpoints: {
    // desktop >= 991
    320: {
      slidesPerView: 1
    },
    768: {
      slidesPerView: 2
    },
  }
});


//  News
var swiperNews = new Swiper(".swiperNews", {
  slidesPerView: 3,
  // loop: true,
  // centeredSlides: true,
  spaceBetween: 30,
  autoplay: {
    delay: 3000,
  },
  grabCursor: true,
  // navigation: {
  //   nextEl: ".swiper-button-next",
  //   prevEl: ".swiper-button-prev",
  // },
  breakpoints: {
    // desktop >= 991
    320: {
      slidesPerView: 1
    },
    768: {
      slidesPerView: 2
    },
    992: {
      slidesPerView: 3,
    }
  }
});

// Our Customer
var swiperCustomer = new Swiper(".swiperCustomer", {
  slidesPerView: 2,
  // disabledClass: 'swiper-button-disabled',
  // loop: true,
  // centeredSlides: true,
  spaceBetween: 30,
  grabCursor: true,
  autoplay: {
    delay: 5000,
  },

  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  breakpoints: {
    // desktop >= 991
    320: {
      slidesPerView: 1
    },
    1200: {
      slidesPerView: 2,
    }
  }
});
const swiperNewsShow = new Swiper(".swiperNewsShow", {
    // Optional parameters
    direction: "horizontal",
    loop: true,
    slidesPerView: 1,
    spaceBetween: 30,
    grabCursor: true,
    // If we need pagination
    pagination: {
      el: ".swiper-pagination",
    },

    // Navigation arrows
    // navigation: {
    //   nextEl: ".swiper-button-next",
    //   prevEl: ".swiper-button-prev",
    // },

    // And if we need scrollbar
    // scrollbar: {
    //   el: ".swiper-scrollbar",
    // },
});

// contacts-select
$(".tab-content").hide();
//show the first tab content
$("#tab-1").show();

$("#select-box").change(function () {
  dropdown = $("#select-box").val();
  //first hide all tabs again when a new option is selected
  $(".tab-content").hide();
  //then show the tab content of whatever option value was selected
  $("#" + "tab-" + dropdown).show();
});
