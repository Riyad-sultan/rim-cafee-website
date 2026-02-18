<?php
include 'includes/db_connect.php';
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rim Caffe & Resturant | Home</title>
     <link rel="stylesheet" href="assets/css/style.css">
     <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
</head>
<body>
    <header class="hero">
    <div class="hero-overlay w-100 h 100 d- flex align-items-center">
       <div class="container">
         <div class="hero-text w-100 mx-auto">

             <h1 class="text-white text-center hero-title mx-auto">
                Wellcome to<span>Rim Caffe & Resturant</span>
             </h1>
             <p class="text-light mb-5 fs-5 mx-auto text-center" style="max-width: 750px; opacity: 0.9; line-height: 1.6;">
                Discover a unique fusion of master-roasted coffee and artisanal gourmet cuisine. 
                    At Rim Caffe, we transform every meal into a celebration of flavor and premium quality.
             </p>
            <div class="d-flex gap-3 justify-content-center"> 
                <a href="menu.php"class="btn-smart">Explore Menu</a>
                <a href="about.php"class="btn-outline-light btn px-4 py-2 rounded-pill" style="text-decoration:none;">Our Story</a>
            </div>
          </div>

        </div>

    </div>

    </header>
    
    <section id="featured" calss="container py-5 mt-5">
        <div class="Section-header">
           <div>
              <h2 style="color: var(--gold);font-family: 'playfair Display';font-size:2.5rem;margin:0;">Featured Selection</h2>
              <p class="text-secondary mb-0">Exprience our most popular handcrafted items.</p>

            </div>
            <a href="menu.php" class="d-none d-md-block" style="color:var(--gold);text-decoration:none;font-weight: 600;">View All <i class="fas fa-arrow-right ms-2"></i>"</a>
            <div class="row g-4">
                <?php
                $query="SELECT * FROM menu WHERE is_avaliable = 1 ORDER BY id DESC LIMIT 6";
                $result=mysqli_query($conn,$query);
                while($row=mysqli_fetch_assoc($result)){
                    $img=!empty($row['image'])? 'assets/images/items/'.$row['image'] : 'assets/images/placeholder.jpg';
                    
                ?>
                <div class="col-12 col-md-6 col-lg-4">
                <div class="menu-card glass-card h-100 d-flex flex-column" style="overflow: hidden; border-radius: 20px;">
                    <div class="card-img-box" style="height: 230px; overflow: hidden;">
                        <img src="<?php echo $img; ?>" alt="<?php echo $row['name']; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;">
                    </div>
                    
                    <div class="menu-info d-flex flex-column flex-grow-1 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h3 style="color: var(--gold); font-family: 'Playfair Display'; font-size: 1.4rem; margin: 0;"><?php echo htmlspecialchars($row['name']); ?></h3>
                            <span class="price" style="color: #fff; font-weight: 600;">$<?php echo number_format($row['price'], 2); ?></span>
                        </div>
                        <p class="text-secondary small mb-4" style="line-height: 1.6;">Prepared fresh daily with premium ingredients and expert care.</p>
                        
                        <a href="menu.php?selected_id=<?php echo $row['id']; ?>" class="btn-smart mt-auto text-center py-2" style="text-decoration: none; font-size: 0.85rem; width: 100%;">
                            Order Now
                        </a>
                    </div>
                </div>
            </div>
            <?php
          } 
            ?> 
            </div>
        </div>
    </section>
    <?php include "includes/footer.php";?>
    
</body>
</html>