package main

import "fmt"

var commits = map[string]int{
	"rsc": 3711,
	"r":   2138,
	"gri": 1908,
	"adg": 912,
}

func main() {
	fmt.Printf("hello, world\n")
	fmt.Println(commits["r"])
}
