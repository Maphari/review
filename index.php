<?php include_once "./db/query.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script defer src="https://kit.fontawesome.com/4441d92e7c.js" crossorigin="anonymous"></script>
    <title>Review</title>
</head>

<body>
    <main class="main-container">
        <header class="main-container__header">
            <h1 class="main-container__header-head">Write your review</h1>
            <p class="main-container__header-para">Your review matters to us!</p>
        </header>
        <section class="main-container__section border mt-7 ">
            <header class="p-3 bg-gray-100 border-b main-container__section-top">
                <h4>Product review </h4>
            </header>
            <section class="py-3 px-20 flex flex-wrap items-center justify-between">
                <div class="flex flex-col gap-4 justify-center items-center">
                    <h3 class="text-xl font-[600]">Enter your review here</h3>
                    <button id="write-review-button"
                        class="mx-auto bg-violet-500 rounded font-medium text-white p-[0.4rem] my-3 transition-all duration-700 ease-linear hover:bg-violet-600">Write
                        review</button>
                </div>

                <div class="w-[30rem]">
                    <?php 
                        for ($i = 5; $i >= 1; $i--):
                            $count = rate_counter($pdo, $i); ?>
                    <div class="flex items-center justify-between gap-1">
                        <span class="text-lg font-medium px-[0.8rem]">
                            <?= $i; ?>
                        </span>
                        <i class="fa-solid fa-star text-sm text-violet-500"></i>
                        <div class="bg-gray-200 flex flex-1 w-[7rem] rounded">
                            <div class="<?php echo show_count_percentage($count) ?> h-4 bg-violet-500 rounded"></div>
                        </div>
                        <span>
                            (<?php if ($count > 0)
                                        echo $count;
                                    else
                                        echo 0; ?>)
                        </span>
                    </div>
                    <?php endfor; ?>
                </div>
                <?php ?>
                <div class=" flex flex-col gap-4 justify-center items-center">
                    <h3 class="text-2xl text-violet-500 font-[600]">
                        <?php echo calculate_rate_avarage($pdo); ?> / 5.0
                    </h3>
                    <div class="flex justify-center items-center gap-1 text-xl my-2">
                        <?php for ($i = 1; $i <= 5; $i++):?>
                        <i
                            class="fa-<?php echo ($i <= calculate_rate_avarage($pdo)) ? "solid" : "regular" ?> p-1 fa-star text-xl text-violet-500"></i>
                        <?php endfor; ?>
                    </div>
                    <div>
                        <h2 class=" text-2xl font-medium">
                            <?php echo countPeopleInDB($pdo); ?>
                        </h2>
                    </div>
                </div>
            </section>
        </section>
        <p>
        </p>
        <!-- Display individual reviews -->
        <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
        <section class="mt-20">
            <section class="flex">
                <div
                    class="flex items-center justify-center text-xl h-[2.8rem] rounded-full w-[3rem] bg-slate-600 text-white font-bold mr-5">
                    <?php echo strtoupper(substr($review["user_name"], 0, 1)); ?>
                </div>
                <section class="w-full border">
                    <div class="w-full bg-gray-100 flex items-center justify-between flex-wrap">
                        <h2 class="p-2 font-bold">
                            <?php echo $review["user_name"]; ?>
                        </h2>
                        <h2 class="p-2 font-bold">
                            <?php echo $review["user_email"]; ?>
                        </h2>
                    </div>
                    <section class="px-3 py-2">
                        <div class="flex gap-1 text-xl my-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i
                                class="fa-<?= ($i <= $review["user_rating"]) ? "solid" : "regular"; ?> p-1 fa-star text-violet-500 text-xl  transition-all duration-700 ease-linear cursor-pointer"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="mb-2 w-[50%]">
                            <?php echo $review["user_review"]; ?>
                        </p>
                    </section>
                    <div class="bg-gray-100 w-full px-2 py-1 flex justify-end">
                        <?php echo $review["created"]; ?>
                    </div>
                </section>
            </section>
        </section>
        <?php endforeach; ?>
        <?php endif; ?>
        <section id="modal"
            class="hidden transition-all duration-700 ease-linear absolute 
             top-0 left-0 w-[100vw] h-[100vh] bg-opacity-75 bg-blur-lg bg-gray-200 backdrop-blur-lg flex items-center justify-center">
            <section id="modal-section-1" class="relative z-[9999] bg-white w-[30rem] rounded opacity-100">
                <div class="border-b flex items-center justify-between p-4">
                    <h3 class="font-bold">Send your review</h3>
                    <i id="close-icon" class="fa-solid fa-xmark p-2 hover:cursor-pointer"></i>
                </div>

                <div class="flex justify-center items-center gap-1 text-xl my-2">
                    <i class="fa-regular p-1 fa-star text-xl text-gray-300 transition-all duration-700 ease-linear hover:text-violet-500 cursor-pointer star star_1"
                        id="rating-star_1" data-rating="1"></i>
                    <i class="fa-regular p-1 fa-star text-xl text-gray-300 transition-all duration-700 ease-linear hover:text-violet-500 cursor-pointer star star_2"
                        id="rating-star_2" data-rating="2"></i>
                    <i class="fa-regular p-1 fa-star text-xl text-gray-300 transition-all duration-700 ease-linear hover:text-violet-500 cursor-pointer star star_3"
                        id="rating-star_3" data-rating="3"></i>
                    <i class="fa-regular p-1 fa-star text-xl text-gray-300 transition-all duration-700 ease-linear hover:text-violet-500 cursor-pointer star star_4"
                        id="rating-star_4" data-rating="4"></i>
                    <i class="fa-regular p-1 fa-star text-xl text-gray-300 transition-all duration-700 ease-linear hover:text-violet-500 cursor-pointer star star_5"
                        id="rating-star_5" data-rating="5"></i>
                </div>

                <p id="error_message" class="text-red-500 px-4"></p>

                <form id="form-review" class="px-4 py-3" method="post" action="./review.php">
                    <input id="usernames" type="text" placeholder="Enter your names"
                        class="outline-none border p-[0.6rem] w-full  rounded" />
                    <input id="email" type="email" placeholder="Enter your email"
                        class="outline-none border p-[0.6rem] w-full my-3 rounded" />
                    <textarea id="review" rows="5" placeholder="Enter your message"
                        class="outline-none border p-[0.6rem] w-full rounded"></textarea>
                    <button id="submit-review"
                        class="p-[0.5rem] w-full mt-2 bg-violet-500 text-white font-medium transition-all duration-700 ease-linear hover:bg-violet-600 rounded">Submit
                        review</button>
                </form>
            </section>
            <section id="modal-section-2"
                class="hidden bg-white items-center rounded py-4 px-3 justify-center w-[20rem] flex flex-col gap-2">
                <i class="fa-solid fa-circle-check text-[3rem] text-green-500"></i>
                <span id="user-thanks" class="text-bold text-lg"></span>
                <span class="mb-3"> Your review has been submitted</span>
            </section>
        </section>
    </main>
    <script type="module" src="./ts/index.js">
    </script>
</body>

</html>