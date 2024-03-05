document.addEventListener('DOMContentLoaded', () => {
   const containers = document.getElementsByClassName('projectCardsContainer')

   Array.from(containers).forEach(container => {
      const cards = container.getElementsByClassName('projectCard')

      Array.from(cards).forEach(card => {
         card.addEventListener('mouseenter', () => {
            card.classList.add('focus')

            Array.from(cards).forEach(otherCard => {
               if (otherCard !== card) {
                  otherCard.classList.add('blur')
               }
            })
         })

         card.addEventListener('mouseleave', () => {
            card.classList.remove('focus')

            Array.from(cards).forEach(otherCard => {
               if (otherCard !== card) {
                  otherCard.classList.remove('blur')
               }
            })
         })
      })
   })
})
