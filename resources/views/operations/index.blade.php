@extends('layouts.main')

@section('title', 'Operations')

@section('content')

  <div class="mt-5">
    @can('manage shifts')
      <a href="{{ route('shifts.index') }}" class="link-button d-inline-block mt-2">Manage Shifts</a>
    @endcan

    @can('view dashboard')
      <a href="{{ route('dashboard') }}" class="link-button d-inline-block mt-2">View Dashboard</a>
    @endcan
  </div>



  <body>
    <!-- Preloader Start -->
    <div class="preloader active">
      <div class="flex-center h-100 bgMainColor">
        <div class="main-container flex-center h-100 flex-column">
          <div class="wave-animation">
            <img src="./assets/img/fav.png" alt="" />
            <div class="waves wave-1"></div>
            <div class="waves wave-2"></div>
            <div class="waves wave-3"></div>
          </div>

          <div class="pt-8">
            <img src="./assets/img/logo.png" alt="" />
          </div>
        </div>
      </div>
    </div>
    <!-- Preloader End -->

    <main class="home-screen">
      <!-- Header Section Start -->
      <section
        class="d-flex justify-content-between align-items-center home-header-section w-100"
      >
        <div class="d-flex justify-content-start align-items-center gap-4">
          <div class="">
            <img src="./assets/img/user_img.png" alt="" />
          </div>
          <div class="">
            <h3 class="heading-3 pb-2">HI, Rayhan!</h3>
            <p
              class="d-inline-flex gap-2 location justify-content-start align-items-center"
            >
              Wembly, HA9 7ND <i class="ph-fill ph-map-pin"></i>
            </p>
          </div>
        </div>

        <div
          class="d-flex justify-content-end align-items-center header-right gap-2 flex-wrap"
        >

          <button class="p-2 flex-center" id="favoriteModalOpenButton">
            <i class="ph ph-list fs-5"></i>
          </button>


          <button class="p-2 flex-center" id="notificationModalOpenButton">
            <i class="ph ph-bell fs-5"></i>

            <span class="notification"></span>
          </button>


          <a href="{{ route('dashboard') }}">
            <button class="p-2 flex-center" id="favoriteModalOpenButton">
                <i class="ph ph-gauge fs-5"></i>
            </button>
          </a>

            <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="p-2 flex-center" type="submit">
                <i class="ph ph-sign-out fs-5"></i>
            </button>
            </form>
        </div>
      </section>
      <!-- Header Section End -->

      <!-- Search Section Start -->
      <section class="search-section w-100 px-6 pt-8">
        <div class="">
          <p class="date">Friday, July 15</p>
          <h2 class="heading-2 pt-2 pb-6">Let’s Find Your Resident</h2>

          <div
            class="search-area d-flex justify-content-between align-items-center gap-2 w-100"
          >
            <div
              class="search-box d-flex justify-content-start align-items-center gap-2 p-3 w-100"
            >
              <div class="flex-center">
                <i class="ph ph-magnifying-glass"></i>
              </div>
              <input type="text" placeholder="Find here..." />
            </div>

            <div class="search-button">
              <button class="flex-center" id="filterModalOpenButton">
                <i class="ph ph-sliders-horizontal"></i>
              </button>
            </div>
          </div>
        </div>
      </section>
      <!-- Search Section End -->



      <!-- Top Doctor Start -->
      <section class="px-6 pt-6 top-doctor-area">
        <div class="d-flex justify-content-between align-items-center">
          <h3>Top Resident</h3>
          <button class="view-all" id="topDoctorModalOpenButton">
            View All
          </button>
        </div>

        <div class="d-flex flex-column gap-4 pt-4">
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-1.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Autistic</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">RMBJ Manor, Flat1, Room 3</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">34</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-2.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Dianne Russell</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Urology</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Christ Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">40</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-3.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Savannah Nguyen</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Psychiatrist</span>
                    <i class="ph ph-dot"></i>
                    <span class="work-place">Cefixime Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">46</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-4.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Dementia </span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Franklin Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">52</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-1.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Autistic</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">JFK Medical Center</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">34</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-2.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Dianne Russell</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Autistic</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Christ Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">40</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-3.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Savannah Nguyen</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Learning Disability</span>
                    <i class="ph ph-dot"></i>
                    <span class="work-place">Cefixime Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">46</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-4.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Cardiologist </span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Franklin Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">52</p>
            </div>
          </div>
        </div>
      </section>
      <!-- Top Doctor End -->

      <!-- Footer Menu Start -->
      <div class="footer-menu-area">
        <div class="footer-menu flex justify-content-center align-items-center">
          <div
            class="d-flex justify-content-between align-items-center px-6 h-100"
          >
            <a href="./home.html" class="flex-center"
              ><i class="ph-fill ph-house link-item active"></i
            ></a>
            <a href="./my-appoinment.html" class="flex-center"
              ><i class="ph ph-calendar link-item"></i
            ></a>
            <a href="./chat-list.html" class="flex-center"
              ><i class="ph ph-messenger-logo link-item"></i
            ></a>
            <a href="./profile-settings.html" class="flex-center"
              ><i class="ph ph-user link-item"></i
            ></a>
          </div>

          <div class="plus-icon position-absolute">
            <div class="position-relative">
              <img src="./assets/img/plus-icon-bg.png" class="" alt="" />
              <i class="ph ph-plus"></i>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer Menu End -->
    </main>

    <!-- ============================= Modal start ========================== -->
    <!-- Notification Modal Start -->
    <div
      class="position-fixed top-0 start-0 bottom-0 end-0 notificationModal overflow-auto fullPageModalClose"
    >
      <div class="px-6 pt-8 notification-top-area">
        <div class="d-flex justify-content-start align-items-center gap-4 py-3">
          <button
            class="back-button flex-center"
            id="notificationModalCloseButton"
          >
            <i class="ph ph-caret-left"></i>
          </button>
          <h2>Notification</h2>
        </div>

        <div
          class="latest-update d-flex justify-content-between align-items-center pt-8 gap2"
        >
          <p class="title">Latest Update</p>
          <div
            class="d-flex justify-content-start align-items-center gap-2 flex-wrap"
          >
            <p>Sort By:</p>
            <div class="position-relative" id="notificationSortBy">
              <p class="select-item">
                <span class="sortByText">All</span>
                <i class="ph ph-caret-down"></i>
              </p>

              <div
                class="notification-sortby-modal modalClose"
                id="notificationSortByModal"
              >
                <ul
                  class="d-flex justify-content-start align-items-start gap-2 flex-column"
                >
                  <li class="sortbyItem">Week</li>
                  <li class="sortbyItem">Month</li>
                  <li class="sortbyItem">Year</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="custom-border-area position-relative mt-5 w-100">

        </div>
      </div>

      <!-- Settings area start -->
      <div class="notification-area pt-5 px-6 d-flex flex-column gap-6 pb-8">
        <div class="">
          <p class="fw-bold n2-color date pb-5">Today, April 20-2022</p>

          <div class="d-flex flex-column gap-4">
            <div
              class="notification-item d-flex justify-content-start align-items-start gap-5"
            >
              <div class="flex-center p-4 icon">
                <i class="ph-fill ph-x"></i>
              </div>
              <div class="">
                <p class="notification-title pb-2">Appointment Cancel!</p>
                <p class="desc n2-color">
                  Never miss a medical appointment with our reliable appointment
                  alarm system!
                </p>
              </div>
            </div>
            <div
              class="notification-item active d-flex justify-content-start align-items-start gap-5"
            >
              <div class="flex-center p-4 icon">
                <i class="ph-fill ph-calendar-check"></i>
              </div>
              <div
                class="d-flex justify-content-start align-items-start flex-column"
              >
                <p class="notification-title pb-2">Schedule Changed</p>
                <p class="desc n2-color pb-4">
                  Schedule Updated! Please check for changes in your
                  appointments.
                </p>
                <a href="" class="primary-button flex-center gap-2"
                  >New <i class="ph ph-arrow-right"></i
                ></a>
              </div>
            </div>
          </div>
        </div>
        <div class="">
          <p class="fw-bold n2-color date pb-5">April 19-2022</p>

          <div class="d-flex flex-column gap-4">
            <div
              class="notification-item d-flex justify-content-start align-items-center gap-5"
            >
              <div class="flex-center p-4 icon">
                <i class="ph-fill ph-calendar-check"></i>
              </div>
              <div class="">
                <p class="notification-title pb-2">Appointment Success!</p>
                <p class="desc n2-color">
                  Your appointment has been successfully scheduled. See you
                  then! Stay healthy!
                </p>
              </div>
            </div>
            <div
              class="notification-item d-flex justify-content-start align-items-center gap-5"
            >
              <div class="flex-center p-4 icon">
                <i class="ph-fill ph-bell-simple"></i>
              </div>
              <div class="">
                <p class="notification-title pb-2">New Services Available!</p>
                <p class="desc n2-color">
                  Explore our expanded range of services for improved health and
                  wellness.
                </p>
              </div>
            </div>
            <div
              class="notification-item d-flex justify-content-start align-items-center gap-5"
            >
              <div class="flex-center p-4 icon">
                <i class="ph-fill ph-credit-card"></i>
              </div>
              <div class="">
                <p class="notification-title pb-2">Credit Card Connected!</p>
                <p class="desc n2-color">
                  Never miss a medical appointment with our reliable appointment
                  alarm system!
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Settings area end -->
    </div>
    <!-- Notification Modal End -->

    <!-- Favourite Doctor Modal start -->
    <div
      class="position-fixed top-0 start-0 bottom-0 end-0 favouriteModal overflow-auto fullPageModalClose"
    >
      <div class="px-6 pt-8 notification-top-area">
        <div class="d-flex justify-content-start align-items-center gap-4 py-3">
          <button class="back-button flex-center" id="favoriteModalCloseButton">
            <i class="ph ph-caret-left"></i>
          </button>
          <h2>My Favourite Residents</h2>
        </div>
      </div>

      <!-- Search Section Start -->
      <div class="search-section w-100 px-6 pt-8">
        <div class="">
          <div
            class="search-area d-flex justify-content-between align-items-center gap-2 w-100"
          >
            <div
              class="search-box d-flex justify-content-start align-items-center gap-2 p-3 w-100"
            >
              <div class="flex-center">
                <i class="ph ph-magnifying-glass"></i>
              </div>
              <input type="text" placeholder="Find here..." />
            </div>

            <div class="search-button">
              <button class="flex-center">
                <i class="ph ph-sliders-horizontal"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Search Section End -->

      <div class="px-6 pt-6 top-doctor-area">
        <div class="d-flex flex-column gap-4 pt-4">
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-1.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Cardiologist</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">JFK Medical</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button active">
                <i class="ph-fill ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">34</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-2.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Dianne Russell</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Urology</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Christ Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button active">
                <i class="ph-fill ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">40</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-3.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Savannah Nguyen</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Psychiatrist</span>
                    <i class="ph ph-dot"></i>
                    <span class="work-place">Cefixime Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button active">
                <i class="ph-fill ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">46</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-4.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Cardiologist </span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Franklin Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button active">
                <i class="ph-fill ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">52</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Find Doctor Modal end -->

    <!-- Search Filter Modal Start -->
    <div
      class="px-6 filter-area position-fixed top-0 start-0 bottom-0 end-0 pb-12 filterModal fullPageModalClose"
    >
      <div class="pt-8 notification-top-area">
        <div class="d-flex justify-content-start align-items-center gap-4 py-3">
          <button class="back-button flex-center" id="filterModalCloseButton">
            <i class="ph ph-caret-left"></i>
          </button>
          <h2>Filter</h2>
        </div>
      </div>

      <!-- Available Section Start -->
      <div class="pt-8">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Available Today</h6>

          <div class="switch">
            <input id="switch-rounded" type="checkbox" />
            <label for="switch-rounded"></label>
          </div>
        </div>
      </div>
      <!-- Available Section end -->

      <!-- Horizontal Line Start -->
      <div class="custom-border-area position-relative mt-5 w-100">

      </div>
      <!-- Horizontal Line End -->

      <!-- Filter Options Start -->
      <div
        class="d-flex justify-content-start align-items-start flex-column gap-8 filter-category-area pt-8"
      >
        <div class="sort-options w-100">
          <h6 class="pb-5">Sort Option</h6>

          <div
            class="p-5 options-box w-100 d-flex flex-column justify-content-start align-items-start gap-4"
          >
            <div class="option-item w-100">
              <label
                class="custom-radio alt-size d-flex justify-content-between align-items-center"
              >
                <span class="desc">Popularity</span>
                <input type="radio" name="gender" value="male" hidden />
                <span class="form-radio-sign"></span>
              </label>

              <div class="custom-border-area position-relative mt-4 w-100">

              </div>
            </div>
            <div class="option-item w-100">
              <label
                class="custom-radio alt-size d-flex justify-content-between align-items-center"
              >
                <span class="desc">Star Rating (higest first)</span>
                <input type="radio" name="gender" value="male" hidden />
                <span class="form-radio-sign"></span>
              </label>

              <div class="custom-border-area position-relative mt-4 w-100">

              </div>
            </div>
            <div class="option-item w-100">
              <label
                class="custom-radio alt-size d-flex justify-content-between align-items-center"
              >
                <span class="desc">Star Rating (Lowest first)</span>
                <input type="radio" name="gender" value="male" hidden />
                <span class="form-radio-sign"></span>
              </label>

              <div class="custom-border-area position-relative mt-4 w-100">

              </div>
            </div>
            <div class="option-item w-100">
              <label
                class="custom-radio alt-size d-flex justify-content-between align-items-center"
              >
                <span class="desc">Best Reviewed First</span>
                <input type="radio" name="gender" value="male" hidden />
                <span class="form-radio-sign"></span>
              </label>

              <div class="custom-border-area position-relative mt-4 w-100">

              </div>
            </div>
            <div class="option-item w-100">
              <label
                class="custom-radio alt-size d-flex justify-content-between align-items-center"
              >
                <span class="desc">Most Reviewed First</span>
                <input type="radio" name="gender" value="male" hidden />
                <span class="form-radio-sign"></span>
              </label>

              <div class="custom-border-area position-relative mt-4 w-100">

              </div>
            </div>
            <div class="option-item w-100">
              <label
                class="custom-radio alt-size d-flex justify-content-between align-items-center"
              >
                <span class="desc">Price (lowest first)</span>
                <input type="radio" name="gender" value="male" hidden />
                <span class="form-radio-sign"></span>
              </label>

              <div class="custom-border-area position-relative mt-4 w-100">

              </div>
            </div>
            <div class="option-item w-100">
              <label
                class="custom-radio alt-size d-flex justify-content-between align-items-center"
              >
                <span class="desc">Price (higest first)</span>
                <input type="radio" name="gender" value="male" hidden />
                <span class="form-radio-sign"></span>
              </label>

              <div class="custom-border-area position-relative mt-4 w-100">

              </div>
            </div>
          </div>
        </div>

        <div class="sort-options w-100">
          <h6 class="pb-5">Gender</h6>
          <div class="d-flex justify-content-between align-items-center gap-4">
            <div class="gender-button">
              <button>Male</button>
            </div>
            <div class="gender-button active">
              <button>Female</button>
            </div>
          </div>
        </div>

        <div class="sort-options w-100">
          <h6 class="pb-5">Work Experience ( years )</h6>
          <div class="d-flex flex-column gap-4">
            <div
              class="d-flex justify-content-between align-items-center gap-4"
            >
              <div class="experience-button active experienceButton">
                <button>Any Experience</button>
              </div>
              <div class="experience-button experienceButton">
                <button>&lt; 1</button>
              </div>
            </div>
            <div
              class="d-flex justify-content-between align-items-center gap-4"
            >
              <div class="experience-button experienceButton">
                <button>1 - 5</button>
              </div>
              <div class="experience-button experienceButton">
                <button>6 - 10</button>
              </div>
            </div>
            <div
              class="d-flex justify-content-between align-items-center gap-4"
            >
              <div class="experience-button experienceButton">
                <button>11 - 15</button>
              </div>
              <div class="experience-button experienceButton">
                <button>16 - 20</button>
              </div>
            </div>
            <div
              class="d-flex justify-content-between align-items-center gap-4"
            >
              <div class="experience-button experienceButton">
                <button>21 - 25</button>
              </div>
              <div class="experience-button experienceButton">
                <button>25+</button>
              </div>
            </div>
          </div>
        </div>

        <div class="schedule-section w-100">
          <div class="d-flex justify-content-between align-items-center pb-5">
            <h6 class="">Schedules</h6>
            <div class="flex-center gap-2">
              <p>January</p>
              <button class="month-change-button flex-center">
                <i class="ph ph-caret-right"></i>
              </button>
            </div>
          </div>

          <div class="schedule-area">
            <div
              class="schedule-day d-flex justify-content-start align-items-center gap-3 overflow-auto"
            >
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">7</span> <span class="date">Sat</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">8</span> <span class="date">Sun</span>
              </button>
              <button class="flex-center flex-column active scheduleButton">
                <span class="fw-semibold">9</span> <span class="date">Mon</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">10</span>
                <span class="date">Tue</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">11</span>
                <span class="date">Wed</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">12</span>
                <span class="date">Thu</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">13</span>
                <span class="date">Fri</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">14</span>
                <span class="date">Sat</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">15</span>
                <span class="date">Sun</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">16</span>
                <span class="date">Mon</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">17</span>
                <span class="date">Tue</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">18</span>
                <span class="date">Wed</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">19</span>
                <span class="date">Thu</span>
              </button>
              <button class="flex-center flex-column scheduleButton">
                <span class="fw-semibold">20</span>
                <span class="date">Fri</span>
              </button>
            </div>
            <div
              class="schedule-time mt-3 d-flex justify-content-start align-items-center gap-3 overflow-auto"
            >
              <button class="flex-center flex-column timeButton">
                10.00 AM
              </button>
              <button class="flex-center flex-column active timeButton">
                11.00 AM
              </button>
              <button class="flex-center flex-column timeButton">
                12.00 PM
              </button>
              <button class="flex-center flex-column timeButton">
                01.00 PM
              </button>
              <button class="flex-center flex-column timeButton">
                02.00 PM
              </button>
              <button class="flex-center flex-column timeButton">
                03.00 PM
              </button>
              <button class="flex-center flex-column timeButton">
                04.00 PM
              </button>
              <button class="flex-center flex-column timeButton">
                05.00 PM
              </button>
            </div>
          </div>
        </div>

        <div class="sort-options w-100 overflow-auto pb-2">
          <h6 class="pb-5">Rating</h6>
          <div class="d-flex justify-content-start align-items-center gap-4">
            <div class="rating-button active ratingButton">
              <button class="flex-center gap-2">
                1.5 <i class="ph-fill ph-star"></i>
              </button>
            </div>
            <div class="rating-button ratingButton">
              <button class="flex-center gap-2">
                2.5 <i class="ph ph-star"></i>
              </button>
            </div>
            <div class="rating-button ratingButton">
              <button class="flex-center gap-2">
                3.5 <i class="ph ph-star"></i>
              </button>
            </div>
            <div class="rating-button ratingButton">
              <button class="flex-center gap-2">
                4 <i class="ph ph-star"></i>
              </button>
            </div>
            <div class="rating-button ratingButton">
              <button class="flex-center gap-2">
                5 <i class="ph ph-star"></i>
              </button>
            </div>
          </div>
        </div>

        <a class="link-button d-block w-100 text-center mb-8">Apply Filters</a>
      </div>
      <!-- Filter Options End -->
    </div>
    <!-- Search Filter Modal End -->

    <!-- Doctor Speciality Modal Start -->
    <div
      class="px-6 pb-8 position-fixed top-0 start-0 bottom-0 end-0 specialityModal fullPageModalClose overflow-auto"
    >
      <div class="px-6 pt-8 notification-top-area">
        <div class="d-flex justify-content-start align-items-center gap-4 py-3">
          <button
            class="back-button flex-center"
            id="specialityModalCloseButton"
          >
            <i class="ph ph-caret-left"></i>
          </button>
          <h2>Doctor Speciality</h2>
        </div>
      </div>

      <!-- speciality section start -->
      <div class="pt-8">
        <div class="row doctor-speciality g-4">
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-6.png" alt="" />
              <p class="title">Surgeon</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >120 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-2.png" alt="" />
              <p class="title">Physician</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >95 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-3.png" alt="" />
              <p class="title">Pediatrician</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >48 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-4.png" alt="" />
              <p class="title">Gynecologist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >65 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-5.png" alt="" />
              <p class="title">Cardiologist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >175 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-6.png" alt="" />
              <p class="title">Dermatologist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >85 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-7.png" alt="" />
              <p class="title">Neurologist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >75 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-8.png" alt="" />
              <p class="title">Psychiatrist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >125 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-9.png" alt="" />
              <p class="title">Oncologist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >110 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-10.png" alt="" />
              <p class="title">Surgeon</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >120 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-4.png" alt="" />
              <p class="title">Radiologist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >135 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-2.png" alt="" />
              <p class="title">Dermatologist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >170 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-3.png" alt="" />
              <p class="title">Pediatrician</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >180 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-4.png" alt="" />
              <p class="title">Gynecologist</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >200 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-5.png" alt="" />
              <p class="title">Physician</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >140 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
          <div class="col-6">
            <div class="item flex-center flex-column px-2 py-4">
              <img src="./assets/img/docton-speciality-icon-6.png" alt="" />
              <p class="title">Surgeon</p>
              <a href="#" class="d-inline-flex align-items-center gap-1"
                >20 doctors <i class="ph ph-arrow-right"></i
              ></a>
            </div>
          </div>
        </div>
      </div>
      <!-- speciality section end -->
    </div>
    <!-- Doctor Speciality Modal End -->

    <!-- Top Doctor Modal Start -->
    <div
      class="topDoctorModal position-fixed top-0 start-0 bottom-0 end-0 fullPageModalClose overflow-auto"
    >
      <div class="px-6 pt-8 notification-top-area">
        <div class="d-flex justify-content-start align-items-center gap-4 py-3">
          <button
            class="back-button flex-center"
            id="topDoctorModalCloseButton"
          >
            <i class="ph ph-caret-left"></i>
          </button>
          <h2>Top Doctor</h2>
        </div>
      </div>

      <!-- Search Section Start -->
      <div class="search-section w-100 px-6 pt-8">
        <div class="">
          <div
            class="search-area d-flex justify-content-between align-items-center gap-2 w-100"
          >
            <div
              class="search-box d-flex justify-content-start align-items-center gap-2 p-3 w-100"
            >
              <div class="flex-center">
                <i class="ph ph-magnifying-glass"></i>
              </div>
              <input type="text" placeholder="Find here..." value="Dr. Diane" />
            </div>

            <div class="search-button">
              <button class="flex-center">
                <i class="ph ph-sliders-horizontal"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- Search Section End -->

      <div class="px-6 search-category-area">
        <div class="overflow-hidden">
          <ul
            class="d-flex justify-content-start search-category align-items-center gap-3 pt-5 overflow-auto"
          >
            <li class="category-item active">All</li>
            <li class="category-item">
              <a href="./top-doctor-search-loading.html" class="p1-color"
                >General</a
              >
            </li>
            <li class="category-item">
              <a href="./top-doctor-search-not-found.html" class="p1-color"
                >Dentist</a
              >
            </li>
            <li class="category-item">Nutritionist</li>
            <li class="category-item">Label</li>
            <li class="category-item">Urologist</li>
            <li class="category-item">Physicial</li>
            <li class="category-item">Gynecologist</li>
          </ul>
        </div>
      </div>

      <!-- Result section -->
      <div class="px-6 pt-6 top-doctor-area">
        <h3>480 Found</h3>
        <div class="d-flex flex-column gap-4 pt-4">
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-1.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Cardiologist</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">JFK Medical</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">34</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-2.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Dianne Russell</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Urology</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Christ Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">40</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-3.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Savannah Nguyen</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Psychiatrist</span>
                    <i class="ph ph-dot"></i>
                    <span class="work-place">Cefixime Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">46</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-4.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Cardiologist </span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Franklin Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">52</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-1.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Cardiologist</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">JFK Medical</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">34</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-2.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Dianne Russell</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Urology</span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Christ Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">40</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-3.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Savannah Nguyen</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Psychiatrist</span>
                    <i class="ph ph-dot"></i>
                    <span class="work-place">Cefixime Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">46</p>
            </div>
          </div>
          <div class="w-100 top-doctor-item p-4">
            <div class="d-flex justify-content-between align-items-start gap-4">
              <div class="d-flex justify-content-start align-items-start gap-4">
                <div class="doctor-img flex-center">
                  <img
                    src="./assets/img/top-doctor-4.png"
                    class="h-100"
                    alt=""
                  />
                  <img src="./assets/img/active.png" class="active" alt="" />
                </div>
                <div class="">
                  <p class="fw-bold name">Dr. Marvin McKinney</p>
                  <p
                    class="d-inline-flex justify-content-start align-items-center py-2 flex-wrap"
                  >
                    <span class="category">Cardiologist </span>
                    <i class="ph ph-dot fs-4"></i>
                    <span class="work-place">Franklin Hospital</span>
                  </p>
                  <div
                    class="d-flex justify-content-start align-items-center flex-wrap"
                  >
                    <div class="rating">
                      <i class="ph-fill ph-star"></i>
                      DOB
                    </div>
                    <i class="ph ph-dot fs-2"></i>
                    <div class="time">
                      <i class="ph-fill ph-clock"></i>24/04/1947
                    </div>
                  </div>
                </div>
              </div>
              <button class="p-2 flex-center favourite-button doctorFavourite">
                <i class="ph ph-heart-straight fs-5 p1-color"></i>
              </button>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-4">
              <a
                href="./doctor-profile.html"
                class="appointment-link d-block p1-color"
                >Paperwork</a
              >
              <div class="custom-border-area position-relative mx-3">

              </div>
              <p class="fs-5 fw-bold">52</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Top Doctor Modal End -->

    <!-- ============================= Modal end ========================== -->

    <!-- Js Dependencies -->
    <script src="./assets/js/plugins/swiper-bundle.min.js"></script>
    <script src="./assets/js/plugins/bootstrap.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/service-worker-settings.js"></script>
  </body>

@endsection
