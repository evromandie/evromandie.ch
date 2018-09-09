---
layout: default
---

<div class="container page">
    <p class="landing-text">Tout ce qu'il y a à connaître sur la mobilité électrique en Suisse romande</p>
    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-info">
                Le site est en construction, n'hésitez pas à faire part de vos expériences et suggestions sur notre forum !
            </div>

            <div markdown="1">

Liens rapides:

- [Communauté]({{ site.forum_url }}) (forum)
- [Liens et ressources](/ressources)

Suivez-nous sur les réseaux sociaux:

<ul>
    {% for link in site.social_links %}
      <li><a href="{{ link.url }}" title="{{ link.title }}"><span class="fa fa-{{ link.icon }}"></span> {{ link.label }}</a></li>
    {% endfor %}
</ul>

</div>
        </div>
        <div class="col-md-6">
            <a class="twitter-timeline" data-lang="fr" data-height="2000" href="https://twitter.com/evromandie">Tweets by RobotsJU</a>
        </div>
    </div>
</div>

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
