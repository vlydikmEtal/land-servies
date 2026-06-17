document.addEventListener("DOMContentLoaded", function () {
  const e = document.getElementById("statsModal"),
    t = document.getElementById("statsModalClose"),
    n = document.getElementById("statsModalNumber"),
    s = document.getElementById("statsModalLabel"),
    d = document.getElementById("statsModalDesc"),
    l = {
      "5+": {
        label: "Опыт работы в уходе за участками",
        desc: "Работаем с 2019 года. За это время мы выстроили стабильный процесс ухода за газонами и садами и обслужили сотни частных участков в Минске и области.",
      },
      "350+": {
        label: "Обслуженных участков в Минске и области",
        desc: "Каждый участок требует индивидуального подхода — мы учитываем состояние почвы, сезон и особенности территории, чтобы результат был аккуратным и стабильным.",
      },
      "100%": {
        label: "Гарантия качества работ",
        desc: "Мы внимательно контролируем каждый этап работ — от покоса газона до стрижки туй, чтобы участок выглядел ухоженно и без пропущенных деталей.",
      },
      "24/7": {
        label: "Поддержка клиентов",
        desc: "Отвечаем на вопросы и помогаем с консультациями по уходу за участком. Связаться с нами можно в любое удобное время.",
      },
    };
  (document.querySelectorAll(".stats-card").forEach((t) => {
    t.addEventListener("click", function () {
      const t = this.querySelector(".stats-number").textContent.trim(),
        o = this.querySelector(".stats-label").textContent.trim(),
        a = l[t] || l["5+"];
      ((n.textContent = t),
        (s.textContent = o),
        (d.textContent = a.desc),
        (e.style.display = "flex"));
    });
  }),
    t.addEventListener("click", function () {
      e.style.display = "none";
    }),
    e.addEventListener("click", function (t) {
      t.target === e && (e.style.display = "none");
    }),
    document.addEventListener("keydown", function (t) {
      "Escape" === t.key &&
        "flex" === e.style.display &&
        (e.style.display = "none");
    }));
});
