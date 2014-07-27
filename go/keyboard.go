package main

import (
	"fmt"

	"github.com/nsf/termbox-go"
)

func draw_all() {
	termbox.Clear(termbox.ColorDefault, termbox.ColorDefault)
	fmt.Printf("Derf Der\n")
}

func draw_ev(ev *termbox.Event) {
	termbox.Clear(termbox.ColorDefault, termbox.ColorDefault)
	fmt.Printf("%v", ev)
}

func main() {
	err := termbox.Init()
	if err != nil {
		panic(err)
	}
	defer termbox.Close()

	termbox.SetInputMode(termbox.InputEsc | termbox.InputMouse)

	termbox.Clear(termbox.ColorDefault, termbox.ColorDefault)
	//draw_keyboard()
	draw_all()

	termbox.Flush()
	//inputmode := 0
	//ctrlxpressed := false

loop:
	for {
		switch ev := termbox.PollEvent(); ev.Type {
		case termbox.EventKey:
			switch ev.Key {
			case termbox.KeyEsc:
				break loop
			}

			draw_ev(&ev)
			//if ev.Key == termbox.KeyCtrlX {
			//ctrlxpressed = true
			//} else {
			//ctrlxpressed = false
			//}
		case termbox.EventResize:
			//draw_all()
		}
	}
}
