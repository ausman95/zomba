import CountUp from "countup";


window.onload = function () {

    let isInViewport = function (elem) {
        var bounding = elem.getBoundingClientRect();
        return (
            bounding.top >= 0 &&
            bounding.left >= 0 &&
            bounding.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            bounding.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    };

    let analyticSections = [
        {id: 'installed-counter', animated: false},
        {id: "deployment-counter", animated: false},
        {id: "experience-counter", animated: false},
    ];

    let animateAnalytic = function (elem_id) {
        const opts = {
            duration: 5,
            useEasing: true,
            useGrouping: true,

        };


        let counters = [
            new CountUp(elem_id, 0, $(`#${elem_id}`).text(), 0, 5, opts),
        ];

        for (let counterItem of counters) {
            counterItem.start();
        }
    };

    window.addEventListener('scroll', function () {

        for (let index = 0; index < analyticSections.length; index++) {
            let sectionElem = document.querySelector('#' + `${analyticSections[index].id}`);
            if (isInViewport(sectionElem) && analyticSections[index].animated === false) {
                animateAnalytic(analyticSections[index].id);
                analyticSections[index].animated = true;
            }
        }
    });

}
