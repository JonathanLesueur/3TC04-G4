<div class="main-container">
    <div class="home-aside aside-col col-xs-12 col-md-3">
        <div class="bg-bgWhite max-size">
            <?php include('./tpl/home/aside.php') ?>
        </div>
    </div>
    <div class="content-col col-xs-12 col-md-9">
        <div class="bg-bgWhite max-size">
            <section class="bloc-asso xs-px-10 xs-py-10">
                <h1 class="bloc-title">Direct'assos</h1>
                <div class="assos-feed">
                <?php for($i = 0; $i < 4; $i++): ?>
                    <div class="asso-item col-xs-12 col-md-6">
                        <a href="" class="content">
                            <div class="text">
                                <h3>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h3>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero facere dolorem optio magni!</p>
                            </div>
                            <img src="https://picsum.photos/750/240" class="" title=""/>
                        </a>
                    </div>
                <?php endfor; ?>
                </div>
            </section>
            <section class="bloc-blog xs-px-10 xs-py-10">
                <h2 class="bloc-title">Articles récents</h2>
                <div class="blog-feed">
                    <?php for($i = 0; $i < 5; $i++): ?>
                    <div class="blog-item">
                        <div class="content-box">
                            <div class="picture-box">
                                <img src="https://picsum.photos/750/240"/>
                            </div>
                            <div class="main-content">
                                <div class="text-content">
                                    <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore...</h3>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Mi tempus imperdiet nulla malesuada pellentesque. Mi tempus imperdiet nulla malesuada pellentesque.</p>
                                </div>
                                <div class="more-content">
                                    <div class="stats">
                                        <div class="comments"><svg class="comment"><use xlink:href="#comment"></use></svg><span>4</span></div>
                                        <div class="likes"><svg class="like"><use xlink:href="#like"></use></svg><span>4</span></div>
                                    </div>
                                    <a href="" class="button orange"><span>Lire la suite</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="author-box">
                            <a href="#">
                                <div class="avatar">
                                    <svg class="user"><use xlink:href="#user"></use></svg>
                                </div>
                                <span class="name">par Jean Dupont</span>
                                <span class="publications">103 publications</span>
                            </a>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </section>
        </div>
    </div>
</div>