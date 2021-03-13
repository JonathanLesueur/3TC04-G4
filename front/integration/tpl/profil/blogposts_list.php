<h2 class="bloc-title">Articles récents</h2>
<div class="blog-feed profil">
    <?php for($i = 0; $i < 7; $i++): ?>
    <div class="blog-item">
        <div class="content-box">
            <div class="picture-box">
                <img src="https://picsum.photos/750/240" />
            </div>
            <div class="main-content">
                <div class="text-content">
                    <h3>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore...</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Mi tempus imperdiet nulla malesuada pellentesque. Mi tempus
                        imperdiet nulla malesuada pellentesque.</p>
                </div>
                <div class="more-content">
                    <div class="stats">
                        <div class="comments"><svg class="comment">
                                <use xlink:href="#comment"></use>
                            </svg><span>4</span></div>
                        <div class="likes"><svg class="like">
                                <use xlink:href="#like"></use>
                            </svg><span>4</span></div>
                    </div>
                    <a href="" class="button orange"><span>Lire la suite</span></a>
                </div>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>