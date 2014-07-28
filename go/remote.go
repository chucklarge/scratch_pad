package main

import (
	"bytes"
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"

	"github.com/nsf/termbox-go"
)

var EventKeyMap = map[termbox.Key]string{
	termbox.KeyArrowUp:    "u", // Curser Up
	termbox.KeyArrowDown:  "d", // Curser Down
	termbox.KeyArrowLeft:  "l", // Curser Left
	termbox.KeyArrowRight: "r", // Curser Right
	termbox.KeyEnter:      "n", // OK
	//termbox.KeyEsc:        "T", // Back
	termbox.KeySpace: "p", // Play/Pause
}

var EventChMap = map[string]string{
	"1": "1",
	"2": "2",
	"3": "3",
	"4": "4",
	"5": "5",
	"6": "6",
	"7": "7",
	"8": "8",
	"9": "9",

	"k": "u", // Curser Up
	"j": "d", // Curser Down
	"h": "l", // Curser Left
	"l": "r", // Curser Right
	",": ",", // Audio
	"w": "w", // Power
	"o": "o", // Home
	"[": "[", // Previous
	"]": "]", // Next
	"d": "d", // Curser Down
	"s": "s", // Setup
	"G": "G", // Options
	"H": "H", // Rewind
	"I": "I", // Forward
	"S": "E", // Search?
}

type Request struct {
	Remote string `json:"remote"`
}

func print_tb(x, y int, fg, bg termbox.Attribute, msg string) {
	for _, c := range msg {
		termbox.SetCell(x, y, c, fg, bg)
		x++
	}
}

func printf_tb(x, y int, fg, bg termbox.Attribute, format string, args ...interface{}) {
	s := fmt.Sprintf(format, args...)
	print_tb(x, y, fg, bg, s)
}

func draw_remote() {
	var W = 79
	var H = 23
	var H_TITLE = 4
	var H_CONTENT = 17
	termbox.SetCell(0, 0, 0x250C, termbox.ColorWhite, termbox.ColorBlack)
	termbox.SetCell(W, 0, 0x2510, termbox.ColorWhite, termbox.ColorBlack)
	termbox.SetCell(0, H, 0x2514, termbox.ColorWhite, termbox.ColorBlack)
	termbox.SetCell(W, H, 0x2518, termbox.ColorWhite, termbox.ColorBlack)

	for i := 1; i < W; i++ {
		termbox.SetCell(i, 0, 0x2500, termbox.ColorWhite, termbox.ColorBlack)
		termbox.SetCell(i, H, 0x2500, termbox.ColorWhite, termbox.ColorBlack)
		termbox.SetCell(i, H_CONTENT, 0x2500, termbox.ColorWhite, termbox.ColorBlack)
		termbox.SetCell(i, H_TITLE, 0x2500, termbox.ColorWhite, termbox.ColorBlack)
	}
	for i := 1; i < H; i++ {
		termbox.SetCell(0, i, 0x2502, termbox.ColorWhite, termbox.ColorBlack)
		termbox.SetCell(W, i, 0x2502, termbox.ColorWhite, termbox.ColorBlack)
	}
	termbox.SetCell(0, H_CONTENT, 0x251C, termbox.ColorWhite, termbox.ColorBlack)
	termbox.SetCell(W, H_CONTENT, 0x2524, termbox.ColorWhite, termbox.ColorBlack)
	termbox.SetCell(0, H_TITLE, 0x251C, termbox.ColorWhite, termbox.ColorBlack)
	termbox.SetCell(W, H_TITLE, 0x2524, termbox.ColorWhite, termbox.ColorBlack)
}

func draw_all(status string) {
	//termbox.Clear(termbox.ColorDefault, termbox.ColorDefault)
	//printf_tb(33, 1, termbox.ColorMagenta|termbox.AttrBold, termbox.ColorBlack, "WDTV Live Remote")
	//printf_tb(21, 2, termbox.ColorMagenta, termbox.ColorBlack, "CTRL+Q to Quit")
	//fmt.Println(status)
}

func draw_ev(ev *termbox.Event) {
	termbox.Clear(termbox.ColorDefault, termbox.ColorDefault)
}

func sendRequest(command string) {
	var url = "http://192.168.1.8:3388/cgi-bin/toServerValue.cgi"

	r := Request{
		Remote: command,
	}
	jsonStr, err := json.MarshalIndent(r, "", "  ")
	if err != nil {
		fmt.Println("No JSON")
		//panic(err)
	}

	req, err := http.NewRequest("POST", url, bytes.NewBuffer(jsonStr))
	req.Header.Set("Content-Type", "application/json")
	client := &http.Client{}

	resp, err := client.Do(req)
	if err != nil {
		fmt.Println("Bad Request")
		//panic(err)
	}
	defer resp.Body.Close()

	body, _ := ioutil.ReadAll(resp.Body)
	//fmt.Println("response Status:", resp.Status)
	//fmt.Println("response Headers:", resp.Header)
	//fmt.Println("response Body:", string(body))
	var status = fmt.Sprintf("%s\t%s\t%s", command, resp.Status, string(body))
	draw_all(status)
}

func main() {
	err := termbox.Init()
	if err != nil {
		panic(err)
	}
	defer termbox.Close()

	termbox.SetInputMode(termbox.InputEsc | termbox.InputMouse)
	draw_remote()
	termbox.Flush()

loop:
	for {
		switch ev := termbox.PollEvent(); ev.Type {
		case termbox.EventKey:
			//fmt.Printf("%+v\n", ev)
			if ev.Key == termbox.KeyCtrlQ {
				break loop
			}
			if ev.Key != 0 {
				sendRequest(EventKeyMap[ev.Key])
			}
			if ev.Ch != 0 {
				//fmt.Printf("%s\n", string(ev.Ch))
				sendRequest(EventChMap[string(ev.Ch)])
			}
		case termbox.EventResize:
			draw_remote()
		}
	}
}
